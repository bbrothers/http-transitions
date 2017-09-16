<?php

namespace Transitions\Console;

use Illuminate\Console\GeneratorCommand;

class GenerateTransition extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:transition {name} {--request-only} {--response-only}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an HTTP transition';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('request-only')) {
            return __DIR__ . '/../../stubs/request_only_transition.stub';
        }
        if ($this->option('response-only')) {
            return __DIR__ . '/../../stubs/response_only_transition.stub';
        }

        return __DIR__ . '/../../stubs/transition.stub';
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {

        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {

        return "$rootNamespace\\Http\\Transitions";
    }
}
