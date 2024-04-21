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
        'pagePrefix' => 'pageIndex',  //page
        'sizePrefix' => 'pageSize',   //size
        'totalPrefix' => 'total',
        'limit' => 10,
        'pageMax' => 500,
    ],

    'field' => [
        'orderPrefix' => '',   //o_
        'searchPrefix' => '',  //s_
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