<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria;

use TimeShow\Repository\Criteria\Common\FieldIsValue;
use TimeShow\Repository\Criteria\Common\FieldLikeValue;
use TimeShow\Repository\Criteria\Common\GreaterThan;
use TimeShow\Repository\Criteria\Common\GreaterThanOrEqual;
use TimeShow\Repository\Criteria\Common\LessThan;
use TimeShow\Repository\Criteria\Common\LessThanOrEqual;
use TimeShow\Repository\Criteria\Common\NotEqual;
use TimeShow\Repository\Criteria\Common\WhereBetween;
use TimeShow\Repository\Criteria\Common\WhereNotBetween;
use TimeShow\Repository\Interfaces\CriteriaInterface;

class RequestCriteriaColumn
{
    public string $orderPrefix;

    public string $searchPrefix;

    public bool $searchable = false;

    public string $searchOption = 'like';

    public bool $sortable = false;

    public string $sortDirection = 'asc';

    public CriteriaInterface $criteria;

    public function __construct(public string $field)
    {
        $this->orderPrefix = config('repository.field.orderPrefix');
        $this->searchPrefix = config('repository.field.searchPrefix');
        $this->criteria = new NullCriteria();
    }

    public function searchByCriteria(CriteriaInterface $criteria): static
    {
        $this->criteria = $criteria;
        $this->searchOption = 'custom';

        return $this;
    }

    public function searchable(bool $searchable): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function search(string $option = 'like'): static
    {
        $this->searchable = true;
        $this->searchOption = $option;

        return $this;
    }

    public function sortByDesc(): static
    {
        $this->sortable = true;
        $this->sortDirection = 'desc';

        return $this;
    }

    public function sortByAsc(): static
    {
        $this->sortable = true;
        $this->sortDirection = 'asc';

        return $this;
    }

    public function searchFieldName(): string
    {
        if (! $this->searchable) {
            return '';
        }

        return $this->searchPrefix.$this->field;
    }

    public function sortableFieldName(): string
    {
        if (! $this->sortable) {
            return '';
        }

        return $this->orderPrefix.$this->field;
    }

    public function matchCriteria($fieldVal): CriteriaInterface
    {
        return match ($this->searchOption) {
            '=' => new FieldIsValue($this->field, $fieldVal),
            '!=' => new NotEqual($this->field, $fieldVal),
            '<' => new LessThan($this->field, $fieldVal),
            '<=' => new LessThanOrEqual($this->field, $fieldVal),
            '>' => new GreaterThan($this->field, $fieldVal),
            '>=' => new GreaterThanOrEqual($this->field, $fieldVal),
            'like' => new FieldLikeValue($this->field, $fieldVal),
            'between' => new WhereBetween($this->field, $fieldVal),
            'notBetween' => new WhereNotBetween($this->field, $fieldVal),
            'custom' => $this->criteria,
            default => new NullCriteria()
        };
    }
}

