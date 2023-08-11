<?php
declare(strict_types=1);
namespace TimeShow\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Http\Request;
use TimeShow\Repository\Criteria\Common\OrderBy;
use TimeShow\Repository\Interfaces\CriteriaInterface;

class RequestCriteria extends AbstractCriteria
{
    protected int $page = 1;

    protected int $pageSize = 10;

    /** @var array<RequestCriteriaColumn> */
    protected array $columns = [];

    protected int $total = 0;

    protected bool $isPagination = true;

    /** @var array<CriteriaInterface> */
    protected array $globalCriteriaList = [];

    protected array $relations = [];

    public function __construct(protected Request $request)
    {
        if ($this->request->query->has('pageIndex')) {
            $this->page = (int) $this->request->query('pageIndex');
        }
        if ($this->request->query->has('pageSize')) {
            $this->pageSize = (int) $this->request->query('pageSize');
        }
    }

    public function disablePagination(): static
    {
        $this->isPagination = false;

        return $this;
    }

    public function globalCriteria(CriteriaInterface $criteria): void
    {
        $this->globalCriteriaList[] = $criteria;
    }

    public function getPaginate(): array
    {
        return [
            'pageSize' => $this->pageSize,
            'pageIndex' => $this->page,
            'total' => $this->total,
        ];
    }

    public static function from(Request $request): static
    {
        return new RequestCriteria($request);
    }

    public function column($field): RequestCriteriaColumn
    {
        $column = new RequestCriteriaColumn($field);
        $this->columns[$column->field] = $column;

        return $column;
    }

    public function columns($defaultColumns = ['id', 'created_at', 'updated_at']): array
    {
        return [...array_keys($this->columns), ...$defaultColumns];
    }

    public function getSearchFieldVal(RequestCriteriaColumn $column, $default = '')
    {
        $searchField = $column->searchFieldName();
        if ($this->request->query->has($searchField)) {
            return (string) $this->request->query->get($searchField);
        }

        return $default;
    }

    public function hasQueryField(string $field): bool
    {
        return $this->request->query->has($field);
    }

    public function getQueryField($field): mixed
    {
        return $this->request->query->get($field);
    }

    public function hasSearchFieldVal(RequestCriteriaColumn $column): bool
    {
        return $this->request->query->has($column->searchFieldName());
    }

    /**
     * @return array<CriteriaInterface>
     */
    public function getSearchCriteriaList(): array
    {
        $list = $this->globalCriteriaList;
        foreach ($this->columns as $column) {
            if ($searchField = $column->searchFieldName()) {
                if (! $this->request->query->has($searchField)) {
                    continue;
                }
                $fieldVal = $this->request->query($searchField);
                $criteria = $column->matchCriteria($fieldVal);
                if (! $criteria instanceof NullCriteria) {
                    $list[] = $criteria;
                }
            }
            if ($sortableField = $column->sortableFieldName()) {
                $direction = $column->sortDirection;
                if ($this->request->query->has($sortableField)) {
                    $directionVal = $this->request->query($sortableField);
                    if ($directionVal === 'asc' || $directionVal === 'desc') {
                        $direction = $directionVal;
                    }
                }
                $list[] = new OrderBy($column->field, $direction);
            }
        }

        return $list;
    }

    protected function applyToQuery(Model|Relation|EloquentBuilder|DatabaseBuilder $model): Model|Relation|DatabaseBuilder|EloquentBuilder
    {
        foreach ($this->getSearchCriteriaList() as $criteria) {
            $model = $criteria->apply($model, $this->repository);
        }

        if ($this->isPagination) {
            $this->total = $model->count();

            return $model->forPage($this->page, $this->pageSize);
        }

        return $model;
    }
}
