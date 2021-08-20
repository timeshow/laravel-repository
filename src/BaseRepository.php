<?php
namespace TimeShow\Repository;

use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use InvalidArgumentException;

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
     * @var App
     */
    protected $app;

    /**
     * @var Model|EloquentBuilder
     */
    protected $model;

    /**
     * Default number of paginated items
     *
     * @var integer
     */
    protected $perPage = 1;

    /**
     * @param App                            $app
     * @param Collection|CriteriaInterface[] $collection
     * @throws RepositoryException
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app            = $app;

        $this->makeModel();
    }

    /**
     * Returns specified model class name.
     *
     * @return string
     */
    public abstract function model();


    /**
     * Creates instance of model to start building query for
     *
     * @param bool $storeModel  if true, this becomes a fresh $this->model property
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel($storeModel = true)
    {
        $model = $this->app->make($this->model());

        if ( ! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        if ($storeModel) $this->model = $model;

        return $model;
    }

    // -------------------------------------------------------------------------
    //      Retrieval methods
    // -------------------------------------------------------------------------

    /**
     * Give unexecuted query for current criteria
     *
     * @return EloquentBuilder
     */
    public function query()
    {
        $this->applyCriteria();

        if ($this->model instanceof Model) {
            return $this->model->query();
        }

        return clone $this->model;
    }

    /**
     * Does a simple count(*) for the model / scope
     *
     * @return int
     */
    public function count()
    {
        return $this->query()->count();
    }

    /**
     * Returns first match
     *
     * @param  array $columns
     * @return Model|null
     */
    public function first($columns = ['*'])
    {
        return $this->query()->first($columns);
    }

    /**
     * Returns first match or throws exception if not found
     *
     * @param  array $columns
     * @return Model
     * @throws ModelNotFoundException
     */
    public function firstOrFail($columns = ['*'])
    {
        $result = $this->query()->first($columns);

        if ( ! empty($result)) return $result;

        throw (new ModelNotFoundException)->setModel($this->model());
    }

    /**
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->query()->get($columns);
    }

    /**
     * @param  string      $value
     * @param  string|null $key
     * @return array
     */
    public function pluck($value, $key = null)
    {
        $this->applyCriteria();

        $lists = $this->model->pluck($value, $key);

        if (is_array($lists)) return $lists;

        return $lists->all();
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
     * @param  int    $perPage
     * @param  array  $columns
     * @param  string $pageName
     * @param  null   $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?: $this->getDefaultPerPage();

        return $this->query()
            ->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param  mixed       $id
     * @param  array       $columns
     * @param  string|null $attribute
     * @return Model|null
     */
    public function find($id, $columns = ['*'], $attribute = null)
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
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->query()->find($id, $columns);

        if ( ! empty($result)) return $result;

        throw (new ModelNotFoundException)->setModel($this->model(), $id);
    }

    /**
     * @param  string $attribute
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->query()
            ->where($attribute, $value)
            ->first($columns);
    }

    /**
     * @param  string $attribute
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = ['*'])
    {
        return $this->query()
            ->where($attribute, $value)
            ->get($columns);
    }

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param  array|Arrayable $where
     * @param  array           $columns
     * @param  bool            $or
     * @return Collection|null
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $model = $this->query();

        foreach ($where as $field => $value) {

            if ($value instanceof Closure) {

                $model = ( ! $or)
                    ? $model->where($value)
                    : $model->orWhere($value);

            } elseif (is_array($value)) {

                if (count($value) === 3) {

                    list($field, $operator, $search) = $value;

                    $model = ( ! $or)
                        ? $model->where($field, $operator, $search)
                        : $model->orWhere($field, $operator, $search);

                } elseif (count($value) === 2) {

                    list($field, $search) = $value;

                    $model = ( ! $or)
                        ? $model->where($field, $search)
                        : $model->orWhere($field, $search);
                }

            } else {
                $model = ( ! $or)
                    ? $model->where($field, $value)
                    : $model->orWhere($field, $value);
            }
        }

        return $model->get($columns);
    }


    // -------------------------------------------------------------------------
    //      Manipulation methods
    // -------------------------------------------------------------------------

    /**
     * Makes a new model without persisting it
     *
     * @param  array $data
     * @return Model
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function make(array $data)
    {
        return $this->makeModel(false)->fill($data);
    }

    /**
     * Creates a model and returns it
     *
     * @param  array $data
     * @return Model|null
     */
    public function create(array $data)
    {
        return $this->makeModel(false)->create($data);
    }

    /**
     * Updates a model by id
     *
     * @param  array       $data
     * @param  mixed       $id
     * @param  string|null $attribute
     * @return bool     false if could not find model or not succesful in updating
     */
    public function update(array $data, $id, $attribute = null)
    {
        $model = $this->find($id, ['*'], $attribute);

        if (empty($model)) return false;

        return $model->fill($data)->save();
    }

    /**
     * Finds and fills a model by id, without persisting changes
     *
     * @param  array       $data
     * @param  mixed       $id
     * @param  string|null $attribute
     * @return Model|false
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $data, $id, $attribute = null)
    {
        $model = $this->find($id, ['*'], $attribute);

        if (empty($model)) {
            throw (new ModelNotFoundException)->setModel($this->model());
        }

        return $model->fill($data);
    }

    /**
     * Deletes a model by id
     *
     * @param  mixed $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->makeModel(false)->destroy($id);
    }


}