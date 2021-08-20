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


}