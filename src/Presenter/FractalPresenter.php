<?php
namespace TimeShow\Repository\Presenter;

use TimeShow\Repository\Helpers\ArraySerializerHelper;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * @template T of TransformerAbstract
 */
class FractalPresenter
{
    protected Manager $manager;
    protected $transformer;

    /**
     * @param  T  $transformer
     */
    public function __construct($transformer)
    {
        $this->transformer = $transformer;
        $this->manager = new Manager();
        $this->manager->setSerializer(new ArraySerializerHelper());
    }

    public function manager(): Manager
    {
        return $this->manager;
    }

    /**
     * @template T
     *
     * @param  T  $transformer
     * @return FractalPresenter<T>
     */
    public static function from($transformer): static
    {
        return new FractalPresenter($transformer);
    }

    public function item($item): array
    {
        return $this->manager->createData(new Item($item, $this->transformer))->toArray();
    }

    public function collection($collection): array
    {
        return $this->manager->createData(new Collection($collection, $this->transformer, 'list'))->toArray();
    }

    /**
     * @return T
     */
    public function transformer()
    {
        return $this->transformer;
    }

}
