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


## Q&A
question1: Unable to locate publishable resources.
``` bash
php artisan cache:clear
php artisan config:clear
```