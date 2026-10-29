<?php

declare(strict_types=1);

namespace Transitions\Console;

use Illuminate\Console\GeneratorCommand;

final class GenerateTransition extends GeneratorCommand
{
    protected $signature = 'make:transition {name} {--request-only} {--response-only}';

    protected $description = 'Create an HTTP transition';

    protected $type = 'Transition';

    protected function getStub(): string
    {
        return TransitionStub::forOptions(
            requestOnly: $this->option('request-only') === true,
            responseOnly: $this->option('response-only') === true,
        )->path();
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Http\\Transitions";
    }
}
