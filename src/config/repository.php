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
        'limit' => 15,
        'pageMax' => 500,
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