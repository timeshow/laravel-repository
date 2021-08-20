# Laravel Repository

## Version Compatibility

 Laravel      | Package
:-------------|:--------
 5.1          | 1.0
 5.2          | 1.2
 5.3          | 1.2
 5.4 to 5.8   | 1.4
 6.0          | 2.0
 7.0, 8.0     | 2.1

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