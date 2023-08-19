<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria\Common;

use TimeShow\Repository\Criteria\AbstractCriteria;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TRelated of \Illuminate\Database\Eloquent\Model
 *
 * @extends AbstractCriteria<TModel, TRelated>
 */
class OrWhereHas extends AbstractCriteria
{

    /**
     * @param string  $relation
     * @param Closure $callback
     * @param string  $operator
     * @param int     $count
     */
    public function __construct(protected string $relation, protected Closure $callback, protected string $operator = '>=', protected int $count = 1)
    {
    }


    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    public function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        return $model->orWhereHas($this->relation, $this->callback, $this->operator, $this->count);
    }

}