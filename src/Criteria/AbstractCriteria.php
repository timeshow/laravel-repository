<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria;

use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use TimeShow\Repository\Interfaces\ExtendedRepositoryInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use TimeShow\Repository\Interfaces\CriteriaInterface;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TRelated of \Illuminate\Database\Eloquent\Model
 *
 * @implements CriteriaInterface<TModel, TRelated>
 */
abstract class AbstractCriteria implements CriteriaInterface
{
    /**
     * @var BaseRepositoryInterface<TModel>
     */
    protected BaseRepositoryInterface $repository;

    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @param BaseRepositoryInterface<TModel>                                   $repository
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    public function apply(Model|Relation|DatabaseBuilder|EloquentBuilder $model, BaseRepositoryInterface $repository): Model|Relation|EloquentBuilder|DatabaseBuilder
    {
        $this->repository = $repository;

        return $this->applyToQuery($model);
    }

    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    abstract protected function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder;

}