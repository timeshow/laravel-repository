<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria\Common;

use TimeShow\Repository\Criteria\AbstractCriteria;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Watson\Rememberable\Query\Builder as RememberableBuilder;

/**
 * Configure default cache duration in config: cache.ttl
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TRelated of \Illuminate\Database\Eloquent\Model
 *
 * @extends AbstractCriteria<TModel, TRelated>
 */
class UseCache extends AbstractCriteria
{
    protected const CACHE_DEFAULT_TTL = 15 * 60;
    protected const CONFIG_TTL_KEY    = 'cache.ttl';

    /**
     * @var int|null
     */
    protected ?int $timeToLive;

    /**
     * @param null|int $timeToLive  in minutes
     */
    public function __construct(?int $timeToLive = null)
    {
        if (empty($timeToLive)) {
            $timeToLive = config(static::CONFIG_TTL_KEY) ?: static::CACHE_DEFAULT_TTL;
        }

        $this->timeToLive = $timeToLive;
    }

    /**
     * @param TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel> $model
     * @return TModel|Relation<TRelated>|DatabaseBuilder|EloquentBuilder<TModel>
     */
    public function applyToQuery(Model|Relation|DatabaseBuilder|EloquentBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        /** @var $model RememberableBuilder */
        return $model->remember($this->timeToLive);
    }
}