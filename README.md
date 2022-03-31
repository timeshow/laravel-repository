# Laravel Repository


## Version Compatibility

 Laravel      | Package
:-------------|:--------
 7.0     | 0.1.0
 8.0     | last version


## Install
Via Composer

``` bash
$ composer require timeshow/laravel-repository
```

If you want to use the repository generator through the `make:repository` Artisan command, add the `RepositoryServiceProvider` to your `config/app.php`:

``` php
TimeShow\Repository\RepositoryServiceProvider::class,
```

Publish the repostory configuration file.

``` bash
php artisan vendor:publish --tag="repository"
```


## Basic Usage

Simply extend the (abstract) repository class of your choice, either `TimeShow\Repository\BaseRepository`, `TimeShow\Repository\ExtendedRepository` or `TimeShow\Repository\ExtendedPostProcessingRepository`.

The only abstract method that must be provided is the `model` method (this is just like the way Bosnadev's repositories are used).

``` bash

    public function count();

    public function first($columns = ['*']);

    public function firstOrFail($columns = ['*']);

    public function all($columns = ['*']);

    public function pluck($value, $key = null);

    public function lists($value, $key = null);

    public function paginate($perPage, $columns = ['*'], $pageName = 'page', $page = null);

    public function find($id, $columns = ['*'], $attribute = null);

    public function findOrFail($id, $columns = ['*']);

    public function findBy($attribute, $value, $columns = ['*']);

    public function findAllBy($attribute, $value, $columns = ['*']);

    public function findWhere($where, $columns = ['*'], $or = false);

    public function findWhereIn($field, array $values, $columns = ['*']);

    public function findWhereNotIn($field, array $values, $columns = ['*']);

    public function findWhereBetween($field, array $values, $columns = ['*']);

    public function make(array $data);

    public function insert(array $data);

    public function create(array $data);

    public function save(array $data);

    public function update(array $data, $id, $attribute = null);

    public function fill(array $data, $id, $attribute = null);

    public function delete($id);

    public function increment($column, $amount = 1);

    public function decrement($column, $amount = 1);
```

### Make Repository

The `make:repository` command automatically creates a new Eloquent model repository class.
It will also attempt to link the correct Eloquent model, but make sure to confirm that it is properly set up.

``` bash
php artisan make:repository Test/TestRepository
```

### Make Service

The `make:service` command automatically creates a new service object class.

``` bash
php artisan make:service Test/TestService
```

### Make Transformer

The `make:transformer` command automatically creates a new transformer array class.

``` bash
php artisan make:transformer Test/TestTransformer
```


## Q&A
question1: Unable to locate publishable resources.
``` bash
php artisan cache:clear
php artisan config:clear
```