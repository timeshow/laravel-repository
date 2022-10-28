<?php
declare(strict_types=1);
namespace TimeShow\Repository;

use Illuminate\Support\ServiceProvider;
use TimeShow\Repository\Console\Commands\MakeRepositoryCommand;
use TimeShow\Repository\Console\Commands\MakeServiceCommand;
use TimeShow\Repository\Console\Commands\MakeTransformerCommand;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * The base package path.
     *
     * @var string|null
     */
    public static string|null $packagePath = null;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        self::$packagePath = __DIR__;

        $this->publishes(
            [
                self::$packagePath . '/config/repository.php' => config_path('repository.php'),
            ],
            'repository'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            MakeRepositoryCommand::class,
            MakeServiceCommand::class,
            MakeTransformerCommand::class,
        ]);
    }

}
