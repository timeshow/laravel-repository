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


### Make Repository

The `make:repository` command automatically creates a new Eloquent model repository class.
It will also attempt to link the correct Eloquent model, but make sure to confirm that it is properly set up.

``` bash
php artisan make:repository Test/TestRepository
```


## Q&A
question1: Unable to locate publishable resources.
``` bash
php artisan cache:clear
php artisan config:clear
```