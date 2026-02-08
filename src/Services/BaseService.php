<?php
namespace TimeShow\Repository\Services;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TimeShow\Repository\Data\Input;
use TimeShow\Repository\Data\ServiceData;
use TimeShow\Repository\Traits\ResponseTrait;

/**
 * Class BaseService
 * @package TimeShow\Repository\Services
 */
class BaseService
{
    use ResponseTrait;

    protected $init = [];
    protected $services = [];
    protected $end = [];

    protected static $container;

    /**
     * 入口方法
     * @param ServiceData|null $data
     * @return ServiceData
     * @throws \Throwable
     */
    final public function __invoke(?ServiceData $data = null): ServiceData
    {
        // 确保 $data 有正确的 input 初始化
        $data = $this->ensureDataInitialized($data);

        try {
            // 执行初始化管道前，再次确保 input 已初始化
            $this->ensureInputInitialized($data);

            $data = $this->executePipeline($this->init, $data);

            $data = $this->before($data);

            $data = $this->executePipeline($this->services, $data);

            $data = $this->behind($data);

            $data = $this->executePipeline($this->end, $data);

            unset($data->input);
            return $data;
        } catch (\Throwable $th) {
            Log::error(sprintf('管道执行失败：%s，堆栈：%s', $th->getMessage(), $th->getTraceAsString()));
            throw $th;
        }
    }

    /**
     * 确保 ServiceData 已正确初始化
     */
    protected function ensureDataInitialized(?ServiceData $data): ServiceData
    {
        if ($data === null) {
            // 创建新的 ServiceData，传入当前请求数据
            $inputData = request()->all();

            // 添加用户信息
            $user = request()->user();
            if ($user) {
                $inputData['user'] = $user;
            }

            $input = new Input($inputData);
            $data = new ServiceData($input);
        }

        return $data;
    }

    /**
     * 确保 input 属性已初始化
     */
    protected function ensureInputInitialized(ServiceData $data): void
    {
        // 检查 input 属性是否已设置且不为 null
        if (!isset($data->input) || $data->input === null) {
            // 获取当前请求数据
            $inputData = request()->all();

            // 如果有用户信息，添加到请求数据中
            $user = request()->user();
            if ($user) {
                $inputData['user'] = $user;
            }

            // 初始化 input
            $data->input = new Input($inputData);
        }
    }

    /**
     * 执行管道
     * @param array $pipeline
     * @param ServiceData $data
     * @return ServiceData
     */
    protected function executePipeline(array $pipeline, ServiceData $data): ServiceData
    {
        foreach ($pipeline as $className) {
            if (!class_exists($className)) {
                throw new \InvalidArgumentException(sprintf('管道类 %s 不存在', $className));
            }

            $instance = static::getContainer()->make($className);

            // 在调用管道项前，确保 input 已初始化
            $this->ensureInputInitialized($data);

            // 支持多种调用方式
            $data = $this->executePipelineItem($instance, $data);
        }

        return $data;
    }

    /**
     * 执行单个管道项
     * @param $instance
     * @param ServiceData $data
     * @return ServiceData
     */
    protected function executePipelineItem($instance, ServiceData $data): ServiceData
    {
        if (method_exists($instance, '__invoke') && is_callable([$instance, '__invoke'])) {
            $result = $instance($data);
        } elseif (method_exists($instance, 'handle')) {
            $result = $instance->handle($data);
        } elseif (method_exists($instance, 'before')) {
            $result = $instance->before($data);
        } elseif (is_callable($instance)) {
            $result = $instance($data);
        } else {
            throw new \RuntimeException(sprintf(
                '管道类 %s 必须实现 __invoke、handle 或 before 方法中的至少一个',
                get_class($instance)
            ));
        }

        if (!$result instanceof ServiceData) {
            throw new \RuntimeException(sprintf(
                '管道类 %s 的返回值必须是 ServiceData 实例，实际返回：%s',
                get_class($instance),
                gettype($result)
            ));
        }

        return $result;
    }

    /**
     * 前置执行方法
     * @param  ServiceData $data
     * @return ServiceData
     */
    protected function before(ServiceData $data): ServiceData
    {
        return $data;
    }

    /**
     * 后置执行方法
     * @param  ServiceData $data
     * @return ServiceData
     */
    protected function behind(ServiceData $data): ServiceData
    {
        return $data;
    }

    /**
     * 执行入口
     */
    public static function run(?Input $input = null, array $constructorParams = []): ServiceData
    {
        try {
            DB::beginTransaction();

            $service = static::makeServiceInstance($constructorParams);

            // 创建 ServiceData，传入 input
            $data = new ServiceData($input);

            // 确保 input 已初始化
            $service->ensureInputInitialized($data);

            $data = $service($data);

            DB::commit();
            Log::info(sprintf('管道 %s 执行成功', static::class));
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error(sprintf('管道 %s 事务回滚：%s', static::class, $th->getMessage()));
            throw $th;
        }
    }

    protected static function makeServiceInstance(array $constructorParams = []): static
    {
        $container = static::getContainer();
        $className = static::class;

        if (!empty($constructorParams)) {
            return $container->makeWith($className, $constructorParams);
        }

        return $container->make($className);
    }

    protected static function getContainer(): Container
    {
        if (!isset(static::$container)) {
            static::$container = Container::getInstance();
        }

        return static::$container;
    }

}
