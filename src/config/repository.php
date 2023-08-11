<?php
/*
|--------------------------------------------------------------------------
| Laravel-Repository Config
|--------------------------------------------------------------------------
*/
return [

    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'pagePrefix' => 'page',
        'sizePrefix' => 'size',
        'totalPrefix' => 'total',
        'limit' => 15,
        'pageMax' => 500,
    ],

    'field' => [
        'orderPrefix' => 'o_',
        'searchPrefix' => 'f_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    */
    'generator'  => [
        'namespace' => 'App\\Repositories',
        'base' => TimeShow\Repository\BaseRepository::class,
        'suffix' => 'Repository',
        'models' => 'App\\Models',
        'basePath'      => app()->path(),
        'rootNamespace' => 'App\\',
        'stubsOverridePath' => app()->path(),
        'paths'         => [
            'repositories' => 'Repositories',
            'interfaces'   => 'Repositories',
            'provider'     => 'RepositoryServiceProvider',
        ]
    ]

];