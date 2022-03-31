<?php
namespace TimeShow\Repository\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use TimeShow\Repository\RepositoryServiceProvider;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service object class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * @var string
     */
    protected $namespace = 'App\\Services';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return RepositoryServiceProvider::$packagePath . '/Console/Commands/stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $this->namespace;
    }
}
