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
        'limit' => 15
    ],

    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    */
    'generator'  => [
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