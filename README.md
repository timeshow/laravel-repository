# Laravel Repository


## Version Compatibility

| Laravel  | Package      |
|:---------|:-------------|
| 7.0      | 0.1.0        |
| 8.0      | 1.0.0        |
| 9.0,10.0 | last version |

## Install
Via Composer

```php
$ composer require timeshow/laravel-repository
```

If you want to use the repository generator through the `make:repository` Artisan command, add the `RepositoryServiceProvider` to your `config/app.php`:

```php   
TimeShow\Repository\RepositoryServiceProvider::class,
```

Publish the repostory configuration file.

```php
php artisan vendor:publish --tag="repository"
```

## Config

You must first configure the storage location of the repository files.
you use it by extending the location repository files of your choice.
```php
    ...
    'pagination' => [
        'pagePrefix' => 'page',  // pageIndex
        'sizePrefix' => 'size',  // pageSize
        'totalPrefix' => 'total', // count
        'limit' => 15,
        'pageMax' => 500,
    ],

    'field' => [
        'orderPrefix' => '',  // o_
        'searchPrefix' => '', // f_
    ],
```

## Basic Usage

Simply extend the (abstract) repository class of your choice, either `TimeShow\Repository\BaseRepository`, `TimeShow\Repository\ExtendedRepository` or `TimeShow\Repository\ExtendedPostProcessingRepository`.

The only abstract method that must be provided is the `model` method (this is just like the way Bosnadev's repositories are used).

```php

    public function count();

    public function first($columns = ['*']);

    public function firstOrFail($columns = ['*']);

    public function all($columns = ['*']);

    public function pluck($value, $key = null);

    public function lists($value, $key = null);

    public function paginate($perPage, $columns = ['*'], $pageName = 'page', $page = null);
    
    public function simplePaginate($perPage, $columns = ['*']);

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

```php
php artisan make:repository Test/TestRepository
```

### Make Service

The `make:service` command automatically creates a new service object class.

```php
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
php artisan route:clear
```

## Getting results from Criteria
```php
$posts = $this->repository->pushCriteria(new OrderBy('id', 'desc'));         
$posts = $this->repository->pushCriteria(new FieldIsValue('name', 'value')); // =
$posts = $this->repository->pushCriteria(new NotEqual('name', 'value'));     // !=
$posts = $this->repository->pushCriteria(new LessThan('name', 'value'));     // <
$posts = $this->repository->pushCriteria(new LessThanOrEqual('name', 'value'));  // <=
$posts = $this->repository->pushCriteria(new GreaterThan('name', 'value'));     // >
$posts = $this->repository->pushCriteria(new GreaterThanOrEqual('name', 'value')); // >=
$posts = $this->repository->pushCriteria(new FieldLikeValue('name', 'value'));     // like
$posts = $this->repository->pushCriteria(new WhereBetween('votes', [1, 100]));     // WhereBetween('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereNotBetween('votes', [1, 100]));     // WhereNotBetween('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereYear('created_at', '2023'));     // WhereYear('created_at', '=', date('Y')
$posts = $this->repository->pushCriteria(new WhereMonth('created_at', '12'));     // WhereMonth('created_at', '=', date('m')
$posts = $this->repository->pushCriteria(new WhereDay('created_at', '06'));     // WhereDay('created_at', '=', date('d')
$posts = $this->repository->pushCriteria(new WhereDate('created_at', '2022-02-06'));     // WhereDate('created_at', '=', date('Y-m-d')
$posts = $this->repository->pushCriteria(new WhereDate('created_at', '<=', '2022-02-06'));     // > >= < <=
$posts = $this->repository->pushCriteria(new WhereTime('created_at', '12:00:00'));     // WhereTime('created_at', '= ', date('H:i:s'))
$posts = $this->repository->pushCriteria(new WhereTime('created_at', ' <= ', '12:00:00'));     // > >= < <=    whereTime('created_at', '= ', date('H:i:s', strtotime('+1 hour')))



```

## Methods
Use Methods: Find all results in Repository.
```php
#通过Repository获取所有结果
$posts = $this->repository->all();

#通过Repository获取分页结果
$posts = $this->repository->paginate($limit = null, $columns = ['*']);

#通过Repository获取分页结果
$posts = $this->repository->simplePaginate($limit = 5, $columns = ['*']);

#通过id获取结果
$post = $this->repository->find($id);

#隐藏Model的属性
$post = $this->repository->hidden(['country_id'])->find($id);

#显示Model指定属性
$post = $this->repository->visible(['id', 'state_id'])->find($id);

#加载Model关联关系
$post = $this->repository->with(['state'])->find($id);

#根据字段名称获取结果
$posts = $this->repository->findBy('country_id', '15');
$posts = $this->repository->findBy('title', $title);

#根据多个字段获取结果
$posts = $this->repository->findWhere([
    //Default Condition =
    'state_id'=>'10',
    'country_id'=>'15',
    //Custom Condition
    ['columnName','>','10']
]);

#根据某一字段的多个值获取结果
$posts = $this->repository->findWhereIn('id', [1,2,3,4,5]);

#获取不包含某一字段的指定值的结果
$posts = $this->repository->findWhereNotIn('id', [6,7,8,9,10]);

#通过自定义scope获取结果
$posts = $this->repository->scopeQuery(function($query){
    return $query->orderBy('sort_order','asc');
})->all();

#在`Repository`中创建数据
$post = $this->repository->create( Input::all() );

#在`Repository`中更新数据
$post = $this->repository->update( Input::all(), $id );

#在`Repository`中删除数据
$this->repository->delete($id)

#在`Repository`中通过多字段删除数据
$this->repository->deleteWhere([
    'state_id'=>'10',
    'country_id'=>'15',
])
```

## Presenter
can you prompt for creating a Transformer too if you haven't already.
```php
use TimeShow\Repository\Presenter\FractalPresenter;

class PostPresenter extends FractalPresenter {

    /**
     * Prepare data to present
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function transformer()
    {
        return new PostTransformer();
    }
}
```
Or enable it in your controller with
```php
$presenter = FractalPresenter::from($this->transformer);
...$presenter->collection($data)
```

## Thanks
---
Thanks for the contributors (github.com)
```bash
Wyj
Harry
```