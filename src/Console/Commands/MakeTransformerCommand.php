<?php
namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTransformerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * @var string
     */
    protected $namespace = 'App\\Transformers';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return self::$packagePath . '/Console/Commands/stubs/transformer.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->namespace;
    }
}
