<?php
namespace TimeShow\Repository\Interfaces;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * Returns specified model class name.
     *
     * Note that this is the only abstract method.
     *
     * @return string
     */
    public function model();

    /**
     * Creates instance of model to start building query for
     *
     * @param bool $storeModel if true, this becomes a fresh $this->model property
     * @return EloquentBuilder
     * @throws RepositoryException
     */
    public function makeModel($storeModel = true);

    /**
     * Give unexecuted query for current criteria
     *
     * @return EloquentBuilder
     */
    public function query();

    /**
     * Does a simple count(*) for the model / scope
     *
     * @return int
     */
    public function count();

    /**
     * Returns first match
     *
     * @param array $columns
     * @return Model|null
     */
    public function first($columns = ['*']);

    /**
     * Returns first match or throws exception if not found
     *
     * @param array $columns
     * @return Model
     * @throws ModelNotFoundException
     */
    public function firstOrFail($columns = ['*']);

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param  string $value
     * @param  string $key
     * @return array
     */
    public function pluck($value, $key = null);

    /**
     * @param  string $value
     * @param  string $key
     * @return array
     * @deprecated
     */
    public function lists($value, $key = null);

    /**
     * @param int    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $columns = ['*'], $pageName = 'page', $page = null);

    /**
     * @param  int|string  $id
     * @param  array       $columns
     * @param  string|null $attribute
     * @return Model|null
     */
    public function find($id, $columns = ['*'], $attribute = null);

    /**
     * Returns first match or throws exception if not found
     *
     * @param  int|string $id
     * @param  array      $columns
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*']);

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*']);

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $columns
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = ['*']);

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $columns
     * @param bool  $or
     *
     * @return Collection|null
     */
    public function findWhere($where, $columns = ['*'], $or = false);

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*']);

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*']);

    /**
     * Find data by between values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereBetween($field, array $values, $columns = ['*']);

    /**
     * Makes a new model without persisting it
     *
     * @param  array $data
     * @return Model
     */
    public function make(array $data);

    /**
     * Insert a model and returns it
     * 插入
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $data);

    /**
     * Creates a model and returns it
     * 创建
     * @param array $data
     * @return Model|null
     */
    public function create(array $data);

    /**
     * Save a model and returns it
     * 保存
     * @param array $data
     * @return Model|null
     */
    public function save(array $data);

    /**
     * Updates a model by $id
     * 更新
     * @param array  $data
     * @param        $id
     * @param string $attribute
     * @return bool  false if could not find model or not succesful in updating
     */
    public function update(array $data, $id, $attribute = null);

    /**
     * Finds and fills a model by id, without persisting changes
     *
     * @param  array  $data
     * @param  mixed  $id
     * @param  string $attribute
     * @return Model|false
     */
    public function fill(array $data, $id, $attribute = null);

    /**
     * Deletes a model by $id
     * 删除
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * Increment a column's value by a given amount
     * 增加
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function increment($column, $amount = 1);

    /**
     * Decrement a column's value by a given amount
     * 减少
     * @param  string  $column
     * @param  float|int  $amount
     * @return int
     */
    public function decrement($column, $amount = 1);

    /**
     * Applies callback to query for easier elaborate custom queries
     * on all() calls.
     *
     * @param Closure $callback must return query/builder compatible
     * @param array   $columns
     * @return Collection
     * @throws \Exception
     */
    public function allCallback(Closure $callback, $columns = ['*']);

    /**
     * Applies callback to query for easier elaborate custom queries
     * on find (actually: ->first()) calls.
     *
     * @param Closure $callback must return query/builder compatible
     * @param array   $columns
     * @return Collection
     * @throws \Exception
     */
    public function findCallback(Closure $callback, $columns = ['*']);


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
    public function defaultCriteria();

    /**
     * Builds the default criteria and replaces the criteria stack to apply with
     * the default collection.
     *
     * @return $this
     */
    public function restoreDefaultCriteria();

    /**
     * Sets criteria to empty collection
     *
     * @return $this
     */
    public function clearCriteria();

    /**
     * Sets or unsets ignoreCriteria flag. If it is set, all criteria (even
     * those set to apply once!) will be ignored.
     *
     * @param bool $ignore
     * @return $this
     */
    public function ignoreCriteria($ignore = true);

    /**
     * Returns a cloned set of all currently set criteria (not including
     * those to be applied once).
     *
     * @return Collection
     */
    public function getCriteria();

    /**
     * Applies Criteria to the model for the upcoming query
     *
     * This takes the default/standard Criteria, then overrides
     * them with whatever is found in the onceCriteria list
     *
     * @return $this
     */
    public function applyCriteria();

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
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria, $key = null);

    /**
     * Removes criteria by key, if it exists
     *
     * @param string $key
     * @return $this
     */
    public function removeCriteria($key);

    /**
     * Pushes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * @param CriteriaInterface $criteria
     * @param string|null       $key
     * @return $this
     */
    public function pushCriteriaOnce(CriteriaInterface $criteria, $key = null);

    /**
     * Removes Criteria, but only for the next call, resets to default afterwards
     * Note that this does NOT work for specific criteria exclusively, it resets
     * to default for ALL Criteria.
     *
     * In effect, this adds a NullCriteria to onceCriteria by key, disabling any criteria
     * by that key in the normal criteria list.
     *
     * @param string $key
     * @return $this
     */
    public function removeCriteriaOnce($key);

}