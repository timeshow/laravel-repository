<?php
namespace TimeShow\Repository\Interfaces;

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

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
interface BaseRepositoryInterface
{
    /**
     * Returns specified model class name.
     *
     * Note that this is the only abstract method.
     *
     * @return class-string<TModel>
     */
    public function model(): string;

    /**
     * Creates instance of model to start building query for
     *
     * @param bool $storeModel if true, this becomes a fresh $this->model property
     * @return TModel
     * @throws RepositoryException
     */
    public function makeModel(bool $storeModel = true): Model;

    /**
     * Give unexecuted query for current criteria
     *
     * @return EloquentBuilder<TModel>|BaseBuilder
     */
    public function query(): EloquentBuilder|BaseBuilder;

    /**
     * Does a simple count(*) for the model / scope
     *
     * @return int
     */
    public function count(): int;

    /**
     * Retrieve the minimum value of a given column
     * @param  string  $column
     * @return mixed
     */
    public function min(string $column): mixed;

    /**
     * Retrieve the maximum value of a given column
     * @param  string  $column
     * @return mixed
     */
    public function max(string $column): mixed;

    /**
     * Retrieve the sum of the values of a given column
     * @param  string  $column
     * @return mixed
     */
    public function sum(string $column): mixed;

    /**
     * Retrieve the average of the values of a given column
     * @param  string  $column
     * @return mixed
     */
    public function avg(string $column): mixed;

    /**
     * Alias for the "avg" method
     * @param  string  $column
     * @return mixed
     */
    public function average(string $column): mixed;

    /**
     * Returns first match
     *
     * @param array $columns
     * @return TModel|null
     */
    public function first(array $columns = ['*']): ?Model;

    /**
     * Returns a new latest first match
     *
     * @param array $columns
     * @param string $sort
     * @param string $skip
     * @return TModel|null
     */
    public function firstLatest(array $columns = ['*'], string $sort='created_at', $skip = 0): ?Model;

    /**
     * Returns a new oldest first match
     *
     * @param array $columns
     * @param string $sort
     * @param string $skip
     * @return TModel|null
     */
    public function firstOldest(array $columns = ['*'], string $sort='created_at', $skip = 0): ?Model;

    /**
     * Returns first match or throws exception if not found
     *
     * @param array $columns
     * @return TModel|null
     * @throws ModelNotFoundException
     */
    public function firstOrFail(array $columns = ['*']): ?Model;

    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']): EloquentCollection;

    /**
     * @param array $columns
     * @return mixed
     */
    public function tree(array $columns = ['*'],$parent_id='parent_id',$id='id',$children='children'): EloquentCollection;

    /**
     * @param array $columns
     * @return mixed
     */
    public function get(array $columns = ['*']): EloquentCollection;

    /**
     * @param  string $value
     * @param  string|null $key
     * @return Collection<int|string, mixed>
     * @throws RepositoryException
     */
    public function pluck(string $value, ?string $key = null): Collection;

    /**
     * @param  string $value
     * @param  string $key
     * @return array
     * @deprecated
     */
    public function lists($value, $key = null);

    /**
     * @param int|null    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param int|null   $page
     * @return LengthAwarePaginator&iterable<int, TModel>
     */
    public function paginate(?int $perPage = null, string|array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator;

    /**
     * Retrieve all data of repository, paginated
     *
     * @param mixed  $perPage
     * @param string|array $columns
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate(mixed $perPage = null, string|array $columns = ['*']);

    /**
     * @param  int|string  $id
     * @param  array       $columns
     * @param  string|null $attribute
     * @return TModel|null
     */
    public function find(int|string $id, array $columns = ['*'], ?string $attribute = null): ?Model;

    /**
     * Returns first match or throws exception if not found
     *
     * @param  int|string $id
     * @param  array      $columns
     * @return TModel
     * @throws ModelNotFoundException
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    /**
     * Find a model by its primary key or return fresh model instance
     *
     * @param  int|string $id
     * @param  array      $columns
     * @return TModel
     * @throws ModelNotFoundException
     */
    public function findOrNew(int|string $id, array $columns = ['*']): Model;

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $columns
     * @return TModel|null
     */
    public function findBy(string $attribute, mixed $value, array $columns = ['*']): ?Model;

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $columns
     * @return EloquentCollection<int, TModel>
     */
    public function findAllBy(string $attribute, mixed $value, array $columns = ['*']): EloquentCollection;

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $columns
     * @param bool  $or
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhere(array $where, array $columns = ['*'], bool $or = false): EloquentCollection;

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereIn($field, array $values, array $columns = ['*']): EloquentCollection;

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereNotIn($field, array $values, array $columns = ['*']): EloquentCollection;

    /**
     * Find data by between values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findWhereBetween($field, array $values, array $columns = ['*']): EloquentCollection;

    /**
     * Makes a new model without persisting it
     *
     * @param  array $data
     * @return TModel
     *
     * @throws MassAssignmentException|RepositoryException
     */
    public function make(array $data): Model;

    /**
     * Insert a model and returns it
     * 插入
     * @param array $data
     * @return TModel|null
     */
    public function insert(array $data): ?Model;

    /**
     * Insert a new record and get the value of the primary key.
     * 插入并返回ID
     * @param array $data
     * @return TModel|null
     */
    public function insertGetId(array $data): ?Model;

    /**
     * Creates a model and returns it
     * 创建
     * @param array $data
     * @return TModel|null
     *
     * @throws RepositoryException
     */
    public function create(array $data): ?Model;

    /**
     * Save a model and returns it
     * 保存
     * @param array $data
     * @return TModel|null
     */
    public function save(array $data): ?Model;

    /**
     * Updates a model by $id
     * 更新
     * @param array<string, mixed>  $data
     * @param int|string $id
     * @param string|null $attribute
     * @return bool  false if could not find model or not succesful in updating
     */
    public function update(array $data, int|string $id, ?string $attribute = null): bool;

    /**
     * Finds and fills a model by id, without persisting changes
     *
     * @param  array<string, mixed>  $data
     * @param  int|string  $id
     * @param  string|null $attribute
     * @return TModel|false
     *
     * @throws MassAssignmentException|ModelNotFoundException
     */
    public function fill(array $data, int|string $id, ?string $attribute = null): Model|false;

    /**
     * Deletes a model by $id
     * 删除
     * @param int|string $id
     * @return int
     */
    public function delete(array|int|string $ids): int;

    /**
     * Increment a column's value by a given amount
     * 增加
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function increment(string $column, float|int $amount = 1): int;

    /**
     * Decrement a column's value by a given amount
     * 减少
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function decrement(string $column, float|int $amount = 1): int;

    /**
     * Applies callback to query for easier elaborate custom queries
     * on all() calls.
     *
     * @param Closure $callback must return query/builder compatible
     * @param array   $columns
     * @return EloquentCollection<int, TModel>
     * @throws RepositoryException
     */
    public function allCallback(Closure $callback, array $columns = ['*']): EloquentCollection;

    /**
     * Applies callback to query for easier elaborate custom queries
     * on find (actually: ->first()) calls.
     *
     * @param Closure $callback must return query/builder compatible
     * @param array   $columns
     * @return TModel|null
     * @throws RepositoryException
     */
    public function findCallback(Closure $callback, array $columns = ['*']): ?Model;

    /**
     * Set hidden fields
     *
     * @param array $fields
     *
     * @return TModel|null
     * @throws RepositoryExceptions
     */
    public function hidden(array $fields): ?Model;

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param null|string $direction
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function orderBy(string $column, null|string $direction = 'asc'): ?Model;

    /**
     * Load relations
     *
     * @param $relations
     *
     * @return TModel|null
     * @throws RepositoryException
     */
    public function with($relations): ?Model;

    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed $relations
     * @return TModel|null
     * @throws RepositoryExceptions
     */
    public function withCount(mixed $relations): ?Model;


    /**
     * Returns a collection with the default criteria for the repository.
     * These should be the criteria that apply for (almost) all calls
     *
     * Default set of criteria to apply to this repository
     * Note that this also needs all the parameters to send to the constructor
     * of each (and this CANNOT be solved by using the classname of as key,
     * since the same Criteria may be applied more than once).
     *
     * @return Collection;
     */
    public function defaultCriteria(): Collection;

    /**
     * Builds the default criteria and replaces the criteria stack to apply with
     * the default collection.
     *
     * @return void
     */
    public function restoreDefaultCriteria(): void;

    /**
     * Sets criteria to empty collection
     *
     * @return void
     */
    public function clearCriteria(): void;

    /**
     * Sets or unsets ignoreCriteria flag. If it is set, all criteria (even
     * those set to apply once!) will be ignored.
     *
     * @param bool $ignore
     * @return void
     */
    public function ignoreCriteria(bool $ignore = true): void;

    /**
     * Returns a cloned set of all currently set criteria (not including
     * those to be applied once).
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getCriteria(): Collection;

    /**
     * Returns a cloned set of all currently set once criteria.
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getOnceCriteria(): Collection;

    /**
     * Returns a cloned set of all currently set criteria (not including
     * those to be applied once).
     *
     * @return Collection<int|string, CriteriaInterface<TModel, Model>>
     */
    public function getAllCriteria(): Collection;

    /**
     * Applies Criteria to the model for the upcoming query
     *
     * This takes the default/standard Criteria, then overrides
     * them with whatever is found in the onceCriteria list
     *
     * @return void
     */
    public function applyCriteria(): void;

    /**
     * Pushes Criteria, optionally by identifying key
     * If a criteria already exists for the key, it is overridden
     *
     * Note that this does NOT overrule any onceCriteria, even if set by key!
     *
     * @param CriteriaInterface $criteria
     * @param string|null       $key          unique identifier to store criteria as
     *                                        this may be used to remove and overwrite criteria
     *                                        empty for normal automatic numeric key
     * @return void
     */
    public function pushCriteria(CriteriaInterface $criteria, ?string $key = null): void;

    /**
     * Removes criteria by key, if it exists
     *
     * @param string $key
     * @return void
     */
    public function removeCriteria(string $key): void;

    /**
     * Pushes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * @param CriteriaInterface $criteria
     * @param string|null       $key
     * @return static
     */
    public function pushOnceCriteria(CriteriaInterface $criteria, ?string $key = null): static;

    /**
     * Removes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * In effect, this adds a NullCriteria to onceCriteria by key, disabling any criteria
     * by that key in the normal criteria list.
     *
     * @param string $key
     * @return static
     */
    public function removeOnceCriteria(string $key): static;

}