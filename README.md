# Laravel Repository


## Version Compatibility

| Laravel | Package      |
|:--------|:-------------|
| 7.0     | 0.1.0        |
| 8.0     | 1.0.0        |
| 9.0     | 2.6.0        |
| 10.0    | last version |

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
    
    public function min(string $column);
    
    public function max(string $column);
    
    public function sum(string $column);
    
    public function avg(string $column);
    
    public function average(string $column);

    public function first($columns = ['*']);

    public function firstLatest(array $columns = ['*'], string $sort='created_at', $skip = 0)

    public function firstOldest(array $columns = ['*'], string $sort='created_at', $skip = 0)

    public function firstOrFail($columns = ['*']);

    public function all(array $columns = ['*']);
    
    public function get(array $columns = ['*']);

    public function pluck($value, $key = null);

    public function lists($value, $key = null);

    public function paginate($perPage, $columns = ['*'], $pageName = 'page', $page = null);
    
    public function simplePaginate($perPage, $columns = ['*']);

    public function find(int|string $id, array $columns = ['*'], ?string $attribute = null);

    public function findOrFail(int|string $id, array $columns = ['*']);
    
    public function findOrNew(int|string $id, array $columns = ['*']);

    public function findBy(string $attribute, mixed $value, array $columns = ['*']);

    public function findAllBy(string $attribute, mixed $value, array $columns = ['*']);

    public function findWhere(array $where, array $columns = ['*'], bool $or = false);

    public function findWhereIn($field, array $values, array $columns = ['*']);

    public function findWhereNotIn($field, array $values, array $columns = ['*']);

    public function findWhereBetween($field, array $values, array $columns = ['*']);

    public function make(array $data);

    public function insert(array $data);
    
    public function insertGetId(array $data);

    public function create(array $data);

    public function save(array $data);

    public function update(array $data, $id, $attribute = null);

    public function fill(array $data, $id, $attribute = null);

    public function delete(array|int|string $ids);

    public function increment(string $column, float|int $amount = 1);

    public function decrement(string $column, float|int $amount = 1);
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
$posts = $this->repository->pushCriteria(new OrderBy('id', 'desc')); // orderBy 排序      
$posts = $this->repository->pushCriteria(new FieldIsValue('name', 'value')); // FieldIsValue 相当于 = or where('name', 'value')
$posts = $this->repository->pushCriteria(new FieldLikeValue('name', 'value'));     // FieldLikeValue 相当于 like 模糊查询 or where('name', 'like', '%'.$value.'%') or like('title','标题')
$posts = $this->repository->pushCriteria(new NotEqual('name', 'value'));     // NotEqual 相当于 !=
$posts = $this->repository->pushCriteria(new LessThan('name', 'value'));     // LessThan 相当于 <
$posts = $this->repository->pushCriteria(new LessThanOrEqual('name', 'value'));  // LessThanOrEqual 相当于 <=
$posts = $this->repository->pushCriteria(new GreaterThan('name', 'value'));     // GreaterThan 相当于 >
$posts = $this->repository->pushCriteria(new GreaterThanOrEqual('name', 'value')); // GreaterThanOrEqual 相当于 >=
$posts = $this->repository->pushCriteria(new WhereNull(['nickname', 'truename'])); // whereNull 验证字段值为空 or whereNull 相当于 is null or WhereNull('nickname')
$posts = $this->repository->pushCriteria(new WhereNotNull('mobile')); // whereNotNull 验证字段不为空 or whereNotNull相当于is not null or WhereNotNull('mobile')
$posts = $this->repository->pushCriteria(new WhereBetween('votes', [1, 100]));     //whereBetween(‘字段’,[范围区间]) 判断字段是否介于范围区间 or WhereBetween('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereNotBetween('votes', [1, 100]));     // whereNotBetween(‘字段’,[范围区间]) 判断字段不在两值之间 or WhereNotBetween('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereIn('votes', [1, 100])); // whereIn(‘字段’,[‘可选值’]) 判断字段是否在数组内 or WhereIn('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereNotIn('votes', [1, 100])); // whereNotIn(‘字段’,[‘可选值’]) 判断指定不在数组内 or WhereNotIn('votes', [1, 100])
$posts = $this->repository->pushCriteria(new WhereYear('created_at', '2023'));     // whereYear(‘字段’,‘年’) 比较年 or WhereYear('created_at', '=', date('Y')
$posts = $this->repository->pushCriteria(new WhereMonth('created_at', '12'));     // whereMonth(‘字段’,‘月份’) 比较字段月份 or WhereMonth('created_at', '=', date('m')
$posts = $this->repository->pushCriteria(new WhereDay('created_at', '06'));     // whereDay(‘字段’,‘天’) 比较某一天 or WhereDay('created_at', '=', date('d')
$posts = $this->repository->pushCriteria(new WhereDate('created_at', '2022-02-06'));     // whereDate(‘字段’,‘2019-9-9’) 比较字段的值和日期 or WhereDate('created_at', '=', date('Y-m-d')
$posts = $this->repository->pushCriteria(new WhereDate('created_at', '<=', '2022-02-06'));     // > >= < <=
$posts = $this->repository->pushCriteria(new WhereTime('created_at', '12:00:00'));     // whereTime(‘字段’,’=’,‘时间’) 比较特定时间 or WhereTime('created_at', '= ', date('H:i:s'))
$posts = $this->repository->pushCriteria(new WhereTime('created_at', ' <= ', '12:00:00'));     // > >= < <=    whereTime('created_at', '= ', date('H:i:s', strtotime('+1 hour')))
$posts = $this->repository->pushCriteria(new whereColumn('created_at', 'updated_at'));     // whereColumn(‘字段1’,‘字段2’) 比较两个字段是否相等(默认=) or whereColumn 相当于 > >= = < <=   or whereColumn('class_id', '=', '5') or  whereColumn('updated_at', '>', 'created_at')
$posts = $this->repository->pushCriteria(new whereColumn([['first_name', '=', 'last_name'], ['updated_at', '>', 'created_at']]));  //whereColumn 方法也可以传递一个包含多个条件的数组。这些条件将使用 and 运算符进行连接

//原生表达式 SelectRaw / WhereRaw / OrWhereRaw / OrderByRaw / HavingRaw / OrHavingRaw
$this->repository->pushCriteria(new SelectRaw('price * ? as price_with_tax', [1.0825]));  // 替代select(DB::raw(…))  select(DB::raw("($sql) as res")) or ->selectRaw('amount + ? as amount_with_bonus', [500])
$this->repository->pushCriteria(new SelectRaw('user_id, sum(views) as total_views'));  // select(DB::raw('user_id, sum(views) as total_views')) or ->groupBy('user_id')->selectRaw('user_id, sum(views) as total_views')->get();
$this->repository->pushCriteria(new WhereRaw('FIND_IN_SET(?, user_group)', '1'));  // where(DB::raw("FIND_IN_SET(3, user_group)")) or ->whereRaw('price > IF(state = "TX", ?, 100)', [200])
$this->repository->pushCriteria(new whereRaw('vip_ID> ? and vip_fenshu >= ?',[2,300]));  //where(DB::raw('vip_ID> ? and vip_fenshu >= ?',[2,300]))->get();//多个条件  or ->whereExists(function ($query) {$query->select(DB::raw(1))->from('orders')->whereRaw('orders.user_id = users.id');})->get();
$this->repository->pushCriteria(new OrWhereRaw('user_group IN (?)', [implode(',', [1, 2, 3, 4])]));  //where(DB::raw('user_group IN (?)', [implode(',', [1, 2, 3, 4])]'user_group IN (?)', [implode(',', [1, 2, 3, 4])]))->get();//多个条件
$this->repository->pushCriteria(new OrderByRaw("FIELD(status, " . implode(", ", [1, 0, 2, 3]) . ")"));  //orderByRaw status字段按着1,0,2,3排序
$this->repository->pushCriteria(new OrderByRaw("FIELD(user_type, 'admin', 'moderator', 'user')"));  // orderBy(DB::raw("FIELD(is_pay,2,0,1)")) or ->orderByRaw("FIELD(is_pay,2,0,1)")->orderByRaw("FIELD(status,1,2,6,7,3)")
$this->repository->pushCriteria(new OrderByRaw('updated_at - created_at DESC'));  // ->orderBy(DB::raw('updated_at - created_at DESC')) or ->orderByRaw('(updated_at - created_at) desc')
$this->repository->pushCriteria(new HavingRaw('COUNT(*) > 10'));  // having(DB::raw("COUNT(*) > 10")) or groupBy('product_id')->havingRaw('COUNT(*) > 1')->get()
$this->repository->pushCriteria(new HavingRaw('SUM(price) > 2500'));  //having(DB::raw('SUM(price) > 2500')) or ->groupBy('department')->havingRaw('SUM(price) > 2500')->get();
$this->repository->pushCriteria(new OrHavingRaw('bid>1'));  //having(DB::raw('bid>1')) or ->selectRaw('bname as title')->groupBy('bid')->orHavingRaw('bid>1')->get()


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

## Search
can you use the search engine to search
```php
$criteria->column('xxx_id')->search();  // default search like
$criteria->column('xxx_id')->search('between');
$criteria->column('xxx_id')->search('=');
$criteria->column('xxx_id')->search('whereIn');
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

You can use return ok/error in your services with BaseService instead of Exception
```php
return $this->ok('success', $data);
return $this->error('error');
```

## Thanks
---
Thanks for the contributors (github.com)
```bash
Wyj
Harry
```