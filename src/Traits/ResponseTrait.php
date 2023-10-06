<?php
namespace TimeShow\Repository\Traits;

/** 接口返回格式定义
 * Class ResponseTrait
 * @package TimeShow\Repository\Traits
 */
trait ResponseTrait
{
    /**
     * 返回成功数据
     * @param string|null $message 返回提示消息
     * @param mixed $data 返回数据
     * @return \Illuminate\Http\JsonResponse
     *
     * @author wei
     */
    public function ok(string|null $message = '操作成功！', mixed $data = []): mixed
    {
        return response()->json([
            'success' => true,
            'code' => '0',
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 返回错误信息
     * @param string|null $message 返回提示消息
     * @param mixed $data 返回数据
     * @return \Illuminate\Http\JsonResponse
     *
     * @author wei
     */
    public function error(string|null $message = '操作失败！', mixed $data = []): mixed
    {
        return response()->json([
            'success' => false,
            'code' => 1,
            'message' => $message,
            'data' => $data,
        ]);
    }
}