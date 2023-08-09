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
use TimeShow\Repository\Criteria\NullCriteria;
use TimeShow\Repository\Interfaces\CriteriaInterface;

class RequestCriteriaColumn
{
    const FIELD_ORDER_PREFIX = 'o_';
    const FIELD_SEARCH_PREFIX = 'f_';
    public bool $searchable = false;
    public bool $searchPrefix = false;
    public string $searchOption = 'like';
    public bool $sortable = false;
    public string $sortDirection = 'asc';
    public CriteriaInterface $criteria;

    public function __construct(public string $field)
    {
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

    public function searchFieldName(bool $searchPrefix = false): string
    {
        if (!$this->searchable) {
            return '';
        }

        $this->searchPrefix = $searchPrefix;
        if ($this->searchPrefix) {
            return self::FIELD_SEARCH_PREFIX . $this->field;
        }
        return $this->field;

    }

    public function sortableFieldName(bool $searchPrefix = false): string
    {
        if (!$this->sortable) {
            return '';
        }

        $this->searchPrefix = $searchPrefix;
        if ($this->searchPrefix) {
            return self::FIELD_ORDER_PREFIX . $this->field;
        }
        return $this->field;
    }

    public function matchCriteria($fieldVal): CriteriaInterface
    {
        return match ($this->searchOption) {
            '=' => new FieldIsValue($this->field, $fieldVal),
            '~=' => new NotEqual($this->field, $fieldVal),
            '<' => new LessThan($this->field, $fieldVal),
            '<=' => new LessThanOrEqual($this->field, $fieldVal),
            '>' => new GreaterThan($this->field, $fieldVal),
            '>=' => new GreaterThanOrEqual($this->field, $fieldVal),
            'like' => new FieldLikeValue($this->field, $fieldVal),
            'custom' => $this->criteria,
            default => new NullCriteria()
        };
    }
}