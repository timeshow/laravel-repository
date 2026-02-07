<?php
declare(strict_types=1);
namespace TimeShow\Repository;

use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use TimeShow\Repository\Interfaces\CriteriaInterface;
use TimeShow\Repository\Criteria\NullCriteria;
use TimeShow\Repository\Exceptions\RepositoryException;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Basic repository for retrieving and manipulating Eloquent models.
 *
 * One of the main differences with Bosnadev's repository is that With this,
 * criteria may be given a key identifier, by which they may later be removed
 * or overriden. This way you can, for instance, set a default criterion for
 * ordering by a certain column, but in other cases, without reinstantiating, order
 * by other columns, by marking the Criteria that does the ordering with key 'order'.
 *
 * implements Contracts\RepositoryInterface, Contracts\RepositoryCriteriaInterface
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var ContainerInterface $app
     */
    protected ContainerInterface $app;

    /**
     * @var Model|EloquentBuilder<TModel>|BaseBuilder
     */
    protected Model|EloquentBuilder|BaseBuilder $modelOrQuery;

    /**
     * Criteria to keep and use for all coming queries
     *
     * @var Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    protected Collection $criteria;

    /**
     * The Criteria to only apply to the next query
     *
     * @var Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    protected Collection $onceCriteria;

    /**
     * List of criteria that are currently active (updates when criteria are stripped)
     * So this is a dynamic list that can change during calls of various repository
     * methods that alter the active criteria.
     *
     * @var Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    protected Collection $activeCriteria;

    /**
     * Whether to skip ALL criteria
     *
     * @var bool
     */
    protected bool $ignoreCriteria = false;

    /**
     * Default number of paginated items
     *
     * @var integer
     */
    protected int $perPage = 15;

    /**
     * @param ContainerInterface                                       $app
     * @param Collection<int|string, CriteriaInterface<TModel, Model>> $initialCriteria
     * @throws RepositoryException
     */
    public function __construct(ContainerInterface $app, Collection $initialCriteria)
    {
        if ($initialCriteria->isEmpty()) {
            $collection = $this->defaultCriteria();
        }

        $this->app            = $app;
        $this->criteria       = $initialCriteria;
        $this->onceCriteria   = new Collection();
        $this->activeCriteria = new Collection();

        $this->makeModel();
    }

    /**
     * Returns specified model class name.
     *
     * @return class-string<TModel>
     */
    public abstract function model(): string;


    /**
     * Creates instance of model to start building query for
     *
     * @param bool $storeModel  if true, this becomes a fresh $this->model property
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel(bool $storeModel = true): Model
    {
        try {
            $model = $this->app->get($this->model());
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $exception) {
            throw new RepositoryException(
                "Class {$this->model()} could not be instantiated through the container",
                $exception->getCode(),
                $exception
            );
        }

        if (! $model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        if ($storeModel) {
            $this->modelOrQuery = $model;
        }

        return $model;
    }

    // -------------------------------------------------------------------------
    //      Retrieval methods
    // -------------------------------------------------------------------------

    /**
     * Give unexecuted query for current criteria
     *
     * @return EloquentBuilder<TModel>|BaseBuilder
     * @throws RepositoryException
     */
    public function query(): EloquentBuilder|BaseBuilder
    {
        $this->applyCriteria();

        if ($this->modelOrQuery instanceof Model) {
            return $this->modelOrQuery->query();
        }

        return clone $this->modelOrQuery;
    }

    /**
     * Does a simple count(*) for the model / scope
     * @param  string  $columns
     * @return int
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * Retrieve the minimum value of a given column
     * @param  string  $column
     * @return float|int
     */
    public function min(string $column): mixed
    {
        return $this->query()->min($column);
    }

    /**
     * Retrieve the maximum value of a given column
     * @param  string  $column
     * @return float|int
     */
    public function max(string $column): mixed
    {
        return $this->query()->max($column);
    }

    /**
     * Retrieve the sum of the values of a given column
     * @param  string  $column
     * @return float|int
     */
    public function sum(string $column): mixed
    {
        return $this->query()->sum($column);
    }

    /**
     * Retrieve the average of the values of a given column
     * @param  string  $column
     * @return float|int
     */
    public function avg(string $column): mixed
    {
        return $this->query()->avg($column);
    }

    /**
     * Alias for the "avg" method
     * @param  string  $column
     * @return float|int
     */
    public function average(string $column): mixed
    {
        return $this->avg($column);
    }

    /**
     * Returns first match
     *
     * @param  array $columns
     * @return Model|null
     */
    public function first(array $columns = ['*']): ?Model
    {
        return $this->query()->first($columns);
    }

    /**
     * Returns a new latest first match
     *
     * @param  array $columns
     * @param string $sort default 'created_at'
     * @param string $skip default 0
     * @return Model|null
     */
    public function firstLatest(array $columns = ['*'], string $sort='created_at', $skip = 0): ?Model
    {
        return $this->query()->latest($sort)->skip($skip)->first($columns);
    }

    /**
     * Returns a new oldest first match
     *
     * @param  array $columns
     * @param string $sort default 'created_at'
     * @param string $skip default 0
     * @return Model|null
     */
    public function firstOldest(array $columns = ['*'], string $sort='created_at', $skip = 0): ?Model
    {
        return $this->query()->oldest($sort)->skip($skip)->first($columns);
    }

    /**
     * Returns first match or throws exception if not found
     *
     * @param  array $columns
     * @return TModel|null
     * @throws ModelNotFoundException
     */
    public function firstOrFail(array $columns = ['*']): ?Model
    {
        $result = $this->query()->first($columns);

        if (! empty($result)) return $result;

        throw (new ModelNotFoundException)->setModel($this->model());
    }

    /**
     * @param  array $columns
     * @return EloquentCollection<int, TModel>
     */
    public function all(array $columns = ['*']): EloquentCollection
    {
        return $this->query()->get($columns);
    }

    /**
     * @param  array $columns
     * @return EloquentCollection<int, TModel>
     */
    public function tree(array $columns = ['*'],$parent_id='parent_id',$id='id',$children='children'): EloquentCollection
    {
        $createTree = function ($items,$parent_id='parent_id',$id='id',$children='children')
        {
            foreach ($items as &$item) {
                $item_id = $item->$id == '' ? 0 : $item->$id ;
                $item_parent_id = $item->$parent_id == '' ? 0 : $item->$parent_id ;

                if(!isset($item->$children)){
                    $item->$children = new EloquentCollection();
                }

                if(!isset($items[$item_parent_id])){
                    $items[$item_parent_id] = new EloquentCollection();
                }
                $parent_item = &$items[$item_parent_id];

                if(!isset($parent_item->$children)){
                    $parent_item->$children = new EloquentCollection();
                }

                $children_tmp = $parent_item->$children;
                $children_tmp[] = $item;
                $parent_item->$children = $children_tmp;
            }

            if(isset($items[0])) return $items[0]->$children ?? new EloquentCollection();
            return new EloquentCollection();
        };

        return $createTree($this->query()->get($columns)->keyBy($id),$parent_id,$id,$children);
    }

    /**
     * @param  array $columns
     * @return EloquentCollection<int, TModel>
     */
    public function get(array $columns = ['*']): EloquentCollection
    {
        return $this->all($columns);
    }

    /**
     * @param  string      $value
     * @param  string|null $key
     * @return Collection<int|string, mixed>
     * @throws RepositoryException
     */
    public function pluck(string $value, ?string $key = null): Collection
    {
        $this->applyCriteria();

        return $this->query()->pluck($value, $key);
    }

    /**
     * @param  string      $value
     * @param  string|null $key
     * @return array
     * @deprecated
     */
    public function lists($value, $key = null)
    {
        return $this->pluck($value, $key);
    }

    /**
     * @param  int|null $perPage
     * @param  array  $columns
     * @param  string $pageName
     * @param  int|null   $page
     * @return LengthAwarePaginator&iterable<int, TModel>
     */
    public function paginate(?int $perPage = null, string|array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->getDefaultPerPage();

        return $this->query()
            ->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param  mixed $perPage
     * @param string|array $columns
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate(mixed $perPage = null, string|array $columns = ['*'], string $pageName = 'page', mixed $page = null)
    {
        $perPage = $perPage ?: $this->getDefaultPerPage();

        return $this->query()
            ->simplePaginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param  mixed       $id
     * @param  array       $columns
     * @param  string|null $attribute
     * @return TModel|null
     */
    public function find(int|string $id, array $columns = ['*'], ?string $attribute = null): ?Model
    {
        $query = $this->query();

        if (null !== $attribute && $attribute !== $query->getModel()->getKeyName()) {
            return $query->where($attribute, $id)->first($columns);
        }

        return $query->find($id, $columns);
    }

    /**
     * Returns first match or throws exception if not found
     *
     * @param int|string $id
     * @param array      $columns
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        $result = $this->query()->find($id, $columns);

        if (! empty($result)) return $result;

        throw (new ModelNotFoundException)->setModel($this->model(), $id);
    }

    /**
     * Find a model by its primary key or return fresh model instance
     *
     * @param int|string $id
     * @param array      $columns
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrNew(int|string $id, array $columns = ['*']): Model
    {
        $result = $this->query()->findOrNew($id, $columns);

        if (! empty($result)) return $result;

        throw (new ModelNotFoundException)->setModel($this->model(), $id);
    }

    /**
     * @param  string $attribute
     * @param  mixed  $value
     * @param  array  $columns
     * @return TModel|null
     */
    public function findBy(string $attribute, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->query()
            ->where($attribute, $value)
            ->first($columns);
    }

    /**
     * @param  string $attribute
     * @param  mixed  $value
     * @param  array  $columns
     * @return EloquentCollection<int, TModel>
     */
    public function findAllBy(string $attribute, mixed $value, $columns = ['*']): EloquentCollection
    {
        return $this->query()
            ->where($attribute, $value)
            ->get($columns);
    }

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param  array<string, callable|array<int, string>|mixed> $where
     * @param  array           $columns
     * @param  bool            $or
     * @return EloquentCollection<int, TModel>
     */
    public function findWhere(array $where, array $columns = ['*'], bool $or = false): EloquentCollection
    {
        $model = $this->query();

        foreach ($where as $field => $value) {

            if ($value instanceof Closure) {

                $model = (! $or)
                    ? $model->where($value)
                    : $model->orWhere($value);

            } elseif (is_array($value)) {

                if (count($value) === 3) {

                    list($field, $operator, $search) = $value;

                    $model = (! $or)
                        ? $model->where($field, $operator, $search)
                        : $model->orWhere($field, $operator, $search);

                } elseif (count($value) === 2) {

                    list($field, $search) = $value;

                    $model = (! $or)
                        ? $model->where($field, $search)
                        : $model->orWhere($field, $search);
                }

            } else {
                $model = (! $or)
                    ? $model->where($field, $value)
                    : $model->orWhere($field, $value);
            }
        }

        return $model->get($columns);
    }

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereIn($field, array $values, $columns = ['*']): EloquentCollection
    {
        return $this->query()
            ->whereIn($field, $values)
            ->get($columns);
    }


    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereNotIn($field, array $values, $columns = ['*']): EloquentCollection
    {
        return $this->query()
            ->whereNotIn($field, $values)
            ->get($columns);
    }

    /**
     * Find data by between values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereBetween($field, array $values, $columns = ['*']): EloquentCollection
    {
        return $this->query()
            ->whereBetween($field, $values)
            ->get($columns);
    }


    // -------------------------------------------------------------------------
    //      Manipulation methods
    // -------------------------------------------------------------------------

    /**
     * Makes a new model without persisting it
     *
     * @param  array $data
     * @return Model
     * @throws MassAssignmentException|RepositoryException
     */
    public function make(array $data): Model
    {
        return $this->makeModel(false)->fill($data);
    }

    /**
     * Creates a model and returns it
     * 插入
     * @param  array $data
     * @return bool
     */
    public function insert(array $data) : bool
    {
        return $this->makeModel(false)->insert($data);
    }

    /**
     * Insert a new record and get the value of the primary key.
     * 插入并返回ID
     * @param  array $data
     * @return int
     */
    public function insertGetId(array $data): int
    {
        return $this->makeModel(false)->insertGetId($data);
    }

    /**
     * Creates a model and returns it
     * 创建
     * @param  array $data
     * @return TModel|null
     * @throws RepositoryException
     */
    public function create(array $data): ?Model
    {
        return $this->makeModel(false)->create($data);
    }

    /**
     * Save a model and returns it
     * 保存
     * @param  array $data
     * @return Model|null
     */
    public function save(array $data): ?Model
    {
        return $this->makeModel(false)->save($data);
    }

    /**
     * Updates a model by id
     * 更新
     * @param  array       $data
     * @param  mixed       $id
     * @param  string|null $attribute
     * @return bool     false if could not find model or not successful in updating
     */
    public function update(array $data, int|string $id, ?string $attribute = null): bool
    {
        $model = $this->find($id, ['*'], $attribute);

        if (empty($model)) return false;

        return $model->fill($data)->save();
    }

    /**
     * Update or Create an entity in repository
     * 更新或创建
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = []) : mixed
    {
        $attributes = array_filter($attributes);
        if (empty($attributes)) {
            return null;
        }

        return $this->query()->updateOrCreate($attributes, $values);
    }

    /**
     * Batch update records matching conditions
     * 批量更新符合条件的记录
     * @param array $conditions  ['status' => 1, 'type' => 'order']
     * @param array $data
     * @param array $orConditions OR optional
     * @return int rows
     */
    public function updateWhere(array $conditions, array $data, array $orConditions = []): int
    {
        if (empty($conditions) || empty($data)) {
            return 0;
        }

        $query = $this->query();

        // AND
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        // OR
        if (!empty($orConditions)) {
            $query->where(function ($q) use ($orConditions) {
                foreach ($orConditions as $field => $value) {
                    if (is_array($value)) {
                        $q->orWhereIn($field, $value);
                    } else {
                        $q->orWhere($field, $value);
                    }
                }
            });
        }

        return $query->update($data);
    }

    /**
     * 批量更新（使用更灵活的查询构建器）
     *
     * @param callable $callback
     * @param array $data
     * @return int
     */
    public function updateWhereCallback(callable $callback, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        $query = $this->query();
        call_user_func($callback, $query);

        return $query->update($data);
    }

    /**
     * 批量更新指定ID的记录
     *
     * @param array $ids
     * @param array $data
     * @return int
     */
    public function updateByIds(array $ids, array $data): int
    {
        if (empty($ids) || empty($data)) {
            return 0;
        }

        return $this->query()->whereIn('id', $ids)->update($data);
    }

    /**
     * Finds and fills a model by id, without persisting changes
     *
     * @param  array       $data
     * @param  mixed       $id
     * @param  string|null $attribute
     * @return TModel|false
     * @throws MassAssignmentException|ModelNotFoundException
     */
    public function fill(array $data, int|string $id, ?string $attribute = null): Model|false
    {
        $model = $this->find($id, ['*'], $attribute);

        if (! $model) {
            throw (new ModelNotFoundException)->setModel($this->model());
        }

        return $model->fill($data);
    }

    /**
     * Delete a model by id
     * 删除
     * @param array|int|string $ids
     * @return int
     */
    public function delete(array|int|string $ids): int
    {
        return $this->makeModel(false)->destroy($ids);
    }

    /**
     * Deletes multiple entities by given criteria
     * 批量删除
     * @param array $where
     * @return int
     */
    public function deleteWhere(array $where): int
    {
        return $this->makeModel(false)->where($where)->delete();
    }

    /**
     * Increment a column's value by a given amount
     * 增加
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function increment(string $column, float|int $amount = 1): int
    {
        return $this->makeModel(false)->increment($column, $amount);
    }

    /**
     * Decrement a column's value by a given amount
     * 减少
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function decrement(string $column, float|int $amount = 1): int
    {
        return $this->makeModel(false)->decrement($column, $amount);
    }

// -------------------------------------------------------------------------
    //      With custom callback
    // -------------------------------------------------------------------------

    /**
     * Applies callback to query for easier elaborate custom queries
     * on all() calls.
     *
     * @param  Closure $callback must return query/builder compatible
     * @param  array   $columns
     * @return EloquentCollection<int, TModel>
     * @throws RepositoryException
     */
    public function allCallback(Closure $callback, array $columns = ['*']): EloquentCollection
    {
        /** @var EloquentBuilder $result */
        $result = $callback($this->query());

        $this->assertValidCustomCallback($result);

        return $result->get($columns);
    }

    /**
     * Applies callback to query for easier elaborate custom queries
     * on find (actually: ->first()) calls.
     *
     * @param  Closure $callback must return query/builder compatible
     * @param  array   $columns
     * @return TModel|null
     * @throws RepositoryException
     */
    public function findCallback(Closure $callback, array $columns = ['*']): ?Model
    {
        /** @var EloquentBuilder $result */
        $result = $callback( $this->query() );

        $this->assertValidCustomCallback($result);

        return $result->first($columns);
    }

    /**
     * Set hidden fields
     *
     * @param array $fields
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function hidden(array $fields): ?Model
    {
        return $this->model()->setHidden($fields);
    }

    /**
     * Set the "orderBy" value of the query.
     *
     * @param string  $column
     * @param null|string $direction
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function orderBy(mixed $column, null|string $direction = 'asc'): ?Model
    {
        return $this->model()->orderBy($column, $direction);
    }

    /**
     * Load relations
     *
     * @param array|string $relations
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function with($relations): ?Model
    {
        return $this->model()->with($relations);
    }

    /**
     * Add subselect queries to count the relations.
     *
     * @param mixed $relations
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function withCount($relations): ?Model
    {
        return $this->model()->withCount($relations);
    }

    /**
     * @param  Model|EloquentBuilder|DatabaseBuilder $result
     * @throws InvalidArgumentException
     */
    protected function checkValidCustomCallback(mixed $result): void
    {
        if (    ! is_a($result, Model::class)
            &&  ! is_a($result, EloquentBuilder::class)
            &&  ! is_a($result, DatabaseBuilder::class)
        ) {
            throw new InvalidArgumentException('Incorrect allCustom call in repository. The callback must return a QueryBuilder/EloquentBuilder or Model object.');
        }
    }

    /**
     * @param  Model|EloquentBuilder|DatabaseBuilder $result
     * @throws InvalidArgumentException
     */
    protected function assertValidCustomCallback(mixed $result): void
    {
        if (
            ! $result instanceof Model
            && ! $result instanceof EloquentBuilder
            && ! $result instanceof BaseBuilder
        ) {
            throw new InvalidArgumentException(
                'Incorrect allCustom call in repository. '
                . 'The callback must return a QueryBuilder/EloquentBuilder or Model object.'
            );
        }
    }


    // -------------------------------------------------------------------------
    //      Criteria
    // -------------------------------------------------------------------------

    /**
     * Returns a collection with the default criteria for the repository.
     * These should be the criteria that apply for (almost) all calls
     *
     * Default set of criteria to apply to this repository
     * Note that this also needs all the parameters to send to the constructor
     * of each (and this CANNOT be solved by using the classname of as key,
     * since the same Criteria may be applied more than once).
     *
     * Override with your own defaults (check ExtendedRepository's refreshed,
     * named Criteria for examples).
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function defaultCriteria(): Collection
    {
        return new Collection();
    }


    /**
     * Builds the default criteria and replaces the criteria stack to apply with
     * the default collection.
     *
     * @return void
     */
    public function restoreDefaultCriteria(): void
    {
        $this->criteria = $this->defaultCriteria();
    }


    /**
     * Sets criteria to empty collection
     *
     * @return void
     */
    public function clearCriteria(): void
    {
        $this->criteria = new Collection();
    }

    /**
     * Sets or unsets ignoreCriteria flag. If it is set, all criteria (even
     * those set to apply once!) will be ignored.
     *
     * @param  bool $ignore
     * @return void
     */
    public function ignoreCriteria(bool $ignore = true): void
    {
        $this->ignoreCriteria = $ignore;
    }

    /**
     * Returns a cloned set of all currently set criteria (not including
     * those to be applied once).
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getCriteria(): Collection
    {
        return clone $this->criteria;
    }

    /**
     * Returns a cloned set of all currently set once criteria.
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getOnceCriteria(): Collection
    {
        return clone $this->onceCriteria;
    }

    /**
     * Returns a cloned set of all currently set criteria (not including
     * those to be applied once).
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getAllCriteria(): Collection
    {
        return $this->getCriteria()->merge($this->getOnceCriteria());
    }

    /**
     * Returns the criteria that must be applied for the next query
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    protected function getCriteriaToApply(): Collection
    {
        // get the standard criteria
        $criteriaToApply = $this->getCriteria();

        // overrule them with criteria to be applied once
        if (! $this->onceCriteria->isEmpty()) {

            foreach ($this->onceCriteria as $onceKey => $onceCriteria) {

                // if there is no key, we can only add the criteria
                if (is_numeric($onceKey)) {

                    $criteriaToApply->push($onceCriteria);
                    continue;
                }

                // if there is a key, override or remove
                // if Null, remove criteria
                if ($onceCriteria instanceof NullCriteria) {

                    $criteriaToApply->forget($onceKey);
                    continue;
                }

                // otherwise, overide the criteria
                $criteriaToApply->put($onceKey, $onceCriteria);
            }
        }

        return $criteriaToApply;
    }

    /**
     * Applies Criteria to the model for the upcoming query
     *
     * This takes the default/standard Criteria, then overrides
     * them with whatever is found in the onceCriteria list
     *
     * @return void
     * @throws RepositoryException
     */
    public function applyCriteria(): void
    {
        // if we're ignoring criteria, the model must be remade without criteria
        if ($this->ignoreCriteria === true) {

            // and make sure that they are re-applied when we stop ignoring
            if ( ! $this->activeCriteria->isEmpty()) {
                $this->makeModel();
                $this->activeCriteria = new Collection();
            }
            return;
        }

        if ($this->areActiveCriteriaUnchanged()) return;

        // if the new Criteria are different, clear the model and apply the new Criteria
        $this->makeModel();

        $this->markAppliedCriteriaAsActive();


        // apply the collected criteria to the query
        foreach ($this->getCriteriaToApply() as $criteria) {
            $this->modelOrQuery = $criteria->apply($this->modelOrQuery, $this);
        }

        $this->clearOnceCriteria();
    }

    /**
     * Checks whether the criteria that are currently pushed
     * are the same as the ones that were previously applied
     *
     * @return bool
     */
    protected function areActiveCriteriaUnchanged(): bool
    {
        return ($this->onceCriteria->isEmpty() &&  $this->criteria == $this->activeCriteria);
    }

    /**
     * Marks the active criteria so we can later check what
     * is currently active
     */
    protected function markAppliedCriteriaAsActive(): void
    {
        $this->activeCriteria = $this->getCriteriaToApply();
    }

    /**
     * After applying, removes the criteria that should only have applied once
     */
    protected function clearOnceCriteria(): void
    {
        if ($this->onceCriteria->isEmpty()) {
            return;
        }

        $this->onceCriteria = new Collection();
    }

    /**
     * Pushes Criteria, optionally by identifying key
     * If a criteria already exists for the key, it is overridden
     *
     * Note that this does NOT overrule any onceCriteria, even if set by key!
     *
     * @param  CriteriaInterface $criteria
     * @param  string|null       $key       unique identifier to store criteria as
     *                                      this may be used to remove and overwrite criteria
     *                                      empty for normal automatic numeric key
     * @return void
     */
    public function pushCriteria(CriteriaInterface $criteria, ?string $key = null): void
    {
        if ($key === null) {
            $this->criteria->push($criteria);
            return;
        }

        // set/override by key
        $this->criteria->put($key, $criteria);
    }

    /**
     * Removes criteria by key, if it exists
     *
     * @param string $key
     * @return void
     */
    public function removeCriteria(string $key): void
    {
        $this->criteria->forget($key);
    }

    /**
     * Pushes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * @param  CriteriaInterface $criteria
     * @param  string|null       $key
     * @return $this
     */
    public function pushOnceCriteria(CriteriaInterface $criteria, ?string $key = null): static
    {
        if ($key === null) {
            $this->onceCriteria->push($criteria);
            return $this;
        }

        // Set/override by key.
        $this->onceCriteria->put($key, $criteria);
        return $this;
    }


    /**
     * Removes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * In effect, this adds a NullCriteria to onceCriteria by key, disabling any criteria
     * by that key in the normal criteria list.
     *
     * @param  string $key
     * @return $this
     */
    public function removeOnceCriteria(string $key): static
    {
        // if not present in normal list, there is nothing to override
        if (! $this->criteria->has($key)) return $this;

        // override by key with Null-value
        $nullCriterion = new NullCriteria();
        $this->onceCriteria->put($key, $nullCriterion);

        return $this;
    }

    // ------------------------------------------------------------------------------
    //      Relation
    // ------------------------------------------------------------------------------




    // ------------------------------------------------------------------------------
    //      Misc.
    // ------------------------------------------------------------------------------

    /**
     * Returns default per page count.
     *
     * @return int
     */
    protected function getDefaultPerPage(): int
    {
        try {
            $perPage = $this->perPage ?: $this->makeModel(false)->getPerPage();
            // Get the per page max
            $perPageMax = config('repository.pagination.pageMax', 1000);
            if ($perPage > $perPageMax) {
                $perPage = $perPageMax;
            }
        } catch (RepositoryException) {
            $perPage = 50;
        }

        return config('repository.pagination.limit', $perPage);
    }


}