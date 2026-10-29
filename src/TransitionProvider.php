<?php

declare(strict_types=1);

namespace Transitions;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Transitions\Console\GenerateTransition;

final class TransitionProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'transitions');

        $this->app->singleton(Config::class, fn (Application $app): Config => Config::fromArray(
            $app->make(Repository::class)->array('transitions'),
        ));

        $this->app->bind(TransitionFactory::class, LaravelTransitionFactory::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('transitions.php'),
            ], 'transitions-config');

            $this->commands([GenerateTransition::class]);
        }
    }
}
