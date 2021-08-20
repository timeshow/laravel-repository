<?php
namespace TimeShow\Repository\Interfaces;

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
     * Returns first match
     *
     * @param array $columns
     * @return Model|null
     */
    public function first($columns = ['*']);

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


}