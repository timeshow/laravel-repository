<?php
namespace TimeShow\Repository;

use TimeShow\Repository\Interfaces\BaseRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;

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


}