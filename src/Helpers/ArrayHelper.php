<?php
namespace TimeShow\Repository\Helpers;

use Illuminate\Support\Arr;

class ArrayHelper
{

    /**
     * multiArray to array
     * @param $multiArray
     * @return array
     * @author wei
     *
     * @example array(array(1, 2, 3), array(4, 5), array(6)) => [1, 2, 3, 4, 5, 6]
     * @example [[1, 2, 3], [4, 5, 6], [7, 8, 9]] => [1, 2, 3, 4, 5, 6, 7, 8, 9]
     */
    public static function collapse($multiArray) : array
    {
        return Arr::collapse($multiArray);
    }

    /**
     * add key and value to array
     * @param $array
     * @param $key
     * @param $value
     * @return array
     *
     * @example add(['name' => 'Desk'], 'price', 100) => ['name' => 'Desk', 'price' =>100]
     * @example add(['name' => 'Desk', 'price' => null], 'price', 100)  => ['name' => 'Desk', 'price' =>100]
     */
    public static function add($array, $key, $value) : array
    {
        return Arr::add($array, $key, $value);
    }

    /**
     * Combine the values in a multidimensional array into a one-dimensional array
     * @param $array
     * @return array
     *
     * @example array('name' => 'Tom', 'languages' => ['PHP', 'Java']) => ["Tom", "PHP", "Java"]
     */
    public static function flatten($array) : array
    {
        return Arr::flatten($array);
    }

    /**
     *
     * @param $array
     * @return array
     *
     * @example ['name' => 'Tom'] => [["name"], ["Tom"]]
     * @example ['name' => ['Tom'], 'languages' => ['PHP', 'Ruby']] => [["name", "languages"], [["Tom"], ["PHP", "Ruby"]]]
     */
    public static function divide($array) : array
    {
        return [$key, $value] = Arr::divide($array);
    }

    /**
     * Remove the specified key value pair from the array
     * @param $array
     * @param $key
     * @return array
     *
     * @example array('name' => ['Joe'], 'age' => ['23'], 'languages' => ['name' => 'PHP', 'Ruby'])
     * @result except($array, ['name', 'age']) => [languages: ["Ruby", name => "PHP"]]
     */
    public static function except($array, $key) : array
    {
        return Arr::except($array, $key);
    }

    /**
     * Return the specified key value pair from the array
     * @param $array
     * @param $key
     * @return array
     *
     * @example array('name' => ['Joe'], 'age' => ['23'], 'languages' => ['name' => 'PHP', 'Ruby'])
     * @result only($array, ['name', 'age']) => [name: ["Joe"], age: ["23"]]
     */
    public static function only($array, $key) : array
    {
        return Arr::only($array, $key);
    }

    /**
     * Return the first element in the array that meets the specified condition
     * @param $array
     * @param $callback
     * @param $default
     * @return mixed
     *
     * @example [100, 200, 300] => 200
     * first($array, function($value){
     *     return $value >= 150;
     * }, '110');
     */
    public static function first($array, $callback, $default) : mixed
    {
        return Arr::first($array, $callback, $default);
    }

    /**
     * Return the last element in the array that meets the specified condition
     * @param $array
     * @param $callback
     * @param $default
     * @return mixed
     *
     * @example [100, 200, 300] => 300
     * last($array, function($value){
     *     return $value >= 150;
     * }, '110');
     */
    public static function last($array, $callback, $default) : mixed
    {
        return Arr::last($array, $callback, $default);
    }

    /**
     * Return the last element in the array that meets the specified condition
     * @param $array
     * @param $callback
     * @param $default
     * @return mixed
     *
     * @example [100, '200', 300, '400', 500] => [1 => '200', 3 => '400']
     * last($array, function($value){
     *     return is_string($value);
     * });
     */
    public static function where($array, $callback) : array
    {
        return Arr::where($array, $callback);
    }

    /**
     * Retrieve all values of a given key from an array
     * @param $array
     * @param $key
     * @param $default
     * @return array
     *
     * @example array(['developer' => ['id' => 1, 'name' => 'Tom']],['developer' => ['id' => 2, 'name' => 'Jack']])
     * @return pluck($array, 'developer.name', 'developer.id') => [1 => 'Tom', 2 => 'Jack']
     */
    public static function pluck($array, $key, $default = null) : array
    {
        return Arr::pluck($array, $key, $default);
    }

    /**
     * Delete a given key value pair from a deeply nested array using the '.' symbol
     * @param $array
     * @param $key
     * @return array
     *
     * @example ['products' => ['desk' => ['price' => 100]]]
     * @example forget($array, 'products.desk') => ['products' =>[]]
     */
    public static function forget($array, $key) : array
    {
        return Arr::forget($array, $key);
    }

    /**
     * Accept a default value
     * @param $array
     * @param $key
     * @param $default
     * @return array
     *
     * @example ['products' => ['desk' => ['price' => 100]]]
     * @example get($array, 'products.desk.price') => 100   get($array, 'products.desk.discount', 0) => 0
     */
    public static function get($array, $key, $default = null) : array
    {
        return Arr::get($array, $key, $default);
    }

    /**
     * Determine whether the specified key or keys exist in the array
     * @param $array
     * @param $key
     * @return bool
     *
     * @example ['products' => ['desk' => ['price' => 100]]]
     * @example Arr::has($array, 'product.name') => true   Arr::has($array, ['product.price', 'product.discount']) => false
     */
    public static function has($array, $key) : bool
    {
        return Arr::has($array, $key);
    }

    /**
     * Use the '.' symbol to determine if any value from the given set exists as a key in the array
     * @param $array
     * @param $key
     * @return bool
     *
     * @example ['products' => ['desk' => ['price' => 100]]]
     * * @example Arr::hasAny($array, ['category', 'product.discount']) => false   Arr::hasAny($array, ['product.price', 'product.discount']) => true
     */
    public static function hasAny($array, $key) : bool
    {
        return Arr::hasAny($array, $key);
    }

    /**
     * Convert an array to a query string
     * @param $array
     * @return string
     *
     * @example ['name'=>'Taylor','order'=>['column'=>'created_at','direction'=>'desc']
     * @return name=Taylor&order[column]=created_at&order[direction]=desc
     */
    public static function query($array) : string
    {
        return Arr::query($array);
    }

    /**
     * Randomly return 1 or n value from an array
     * @param $array
     * @param $num
     * @return string
     *
     * @example [1, 2, 3, 4, 5] => [1,2]
     */
    public static function random($array, $num = null) : string
    {
        return Arr::random($array, $num);
    }

    /**
     * Flatten all keys in a multidimensional array to a one-dimensional array
     * @param $array
     * @return array
     * @example ['products' => ['desk' => ['price' => 100]]] => ['products.desk.price' => 100]
     */
    public static function dot($array) : array
    {
        return Arr::dot($array);
    }

    /**
     * Expanding a one-dimensional array using 'point notation' to a multidimensional array
     * @param $array
     * @return array
     *
     * @example ['user.name' => 'Kevin Malone','user.occupation' => 'Accountant'] => ['user' => ['name' => 'Kevin Malone', 'occupation' => 'Accountant']]
     */
    public static function undot($array) : array
    {
        return Arr::undot($array);
    }

    /**
     * Use the '.' symbol to set missing values for multidimensional arrays or objects
     * @param $array
     * @param $key
     * @param $default
     * @return array
     *
     * @example  ['products' => ['desk' => ['price' => 100]]] => ['products' => ['desk' => ['price' => 100, 'discount' => 10]]]
     */
    public static function data_fill($array, $key, $default = null) : array
    {
        return data_fill($array, $key, $default);
    }

    /**
     * Use the '.' symbol to retrieve values from a multidimensional array or object based on a specified key
     * @param $array
     * @param $key
     * @param $default
     * @return array
     *
     * @example ['products' => ['desk' => ['price' => 100]]] => 100 or data_get($data, 'products.desk.discount', 0) => 0
     */
    public static function data_get($array, $key, $default = null) : array
    {
        return data_get($array, $key, $default);
    }

    /**
     * Use the '.' symbol to set values from multidimensional arrays or objects based on specified keys
     * @param $array
     * @param $key
     * @param $default
     * @param bool $overwrite
     * @return array
     *
     * @example ['products' => ['desk' => ['price' => 100]]]
     * @result data_set($data, 'products.desk.price', 200, $overwrite = false) => ['products' => ['desk' => ['price' => 100]]]
     */
    public static function data_set($array, $key, $default = null, bool $overwrite = false) : array
    {
        return data_set($array, $key, $default, $overwrite);
    }

}
