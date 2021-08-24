<?php
namespace TimeShow\Repository\Criteria;

use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use TimeShow\Repository\Interfaces\ExtendedRepositoryInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use TimeShow\Repository\Interfaces\CriteriaInterface;

abstract class AbstractCriteria implements CriteriaInterface
{
    /**
     * @var BaseRepositoryInterface|ExtendedRepositoryInterface
     */
    protected $repository;

    /**
     * @param Model|DatabaseBuilder|EloquentBuilder|RememberableBuilder $model
     * @param BaseRepositoryInterface|ExtendedRepositoryInterface       $repository
     * @return mixed
     */
    public function apply($model, BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;

        return $this->applyToQuery($model);
    }

    /**
     * @param $model
     * @return mixed
     */
    abstract protected function applyToQuery($model);

}