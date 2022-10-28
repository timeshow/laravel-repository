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
class Has extends AbstractCriteria
{

    /**
     * @param string  $relation
     * @param string  $operator
     * @param int     $count
     * @param string  $boolean
     * @param Closure|null $callback
     */
    public function __construct(protected string $relation, protected string $operator = '>=', protected int $count = 1, protected string $boolean = 'and', protected ?Closure $callback = null)
    {
    }


    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    public function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        return $model->has($this->relation, $this->operator, $this->count, $this->boolean, $this->callback);
    }

}