<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use TimeShow\Repository\Interfaces\CriteriaInterface;

class ComposeCriteria implements CriteriaInterface
{
    /**
     * @param array<CriteriaInterface> $criteriaList
     */
    public function __construct(protected array $criteriaList = [])
    {
    }

    public static function from(): static
    {
        return new ComposeCriteria();
    }

    public function when(bool $condition, CriteriaInterface $criteria): static
    {
        if ($condition) {
            return $this->push($criteria);
        }
        return $this;
    }

    public function push(CriteriaInterface $criteria): static
    {
        if (!$criteria instanceof NullCriteria) {
            $this->criteriaList[] = $criteria;
        }
        return $this;
    }

    public function apply(Model|Relation|EloquentBuilder|DatabaseBuilder $model, BaseRepositoryInterface $repository): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        foreach ($this->criteriaList as $criteria) {
            $model = $criteria->apply($model, $repository);
        }
        return $model;
    }
}