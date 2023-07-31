<?php

declare(strict_types=1);

namespace TimeShow\Repository\Criteria\Common;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use TimeShow\Repository\Criteria\AbstractCriteria;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TRelated of \Illuminate\Database\Eloquent\Model
 *
 * @extends AbstractCriteria<TModel, TRelated>
 */
class FieldOrLikeValue extends AbstractCriteria
{
    public function __construct(protected string $field, protected mixed $value = true)
    {
    }

    /**
     * @param  TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>  $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    public function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        return $model->orWhere($this->field, 'like', '%'.$this->value.'%');
    }
}
