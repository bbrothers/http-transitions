<?php

namespace Transitions;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Transitions\Console\GenerateTransition;

class TransitionProvider extends ServiceProvider
{

    public function boot() : void
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('transitions.php'),
            ]);
        }
    }

    public function register() : void
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'transitions');

        $this->app->bind(TransitionFactory::class, function (Application $app) {

            return new LaravelTransitionFactory($app);
        });

        $this->app->bind(TransitionMiddleware::class, function (Application $app) {

            $config = Config::fromArray($app->make('config')->get('transitions'));
            return new TransitionMiddleware($config, $app->make(TransitionFactory::class));
        });

        $this->commands([GenerateTransition::class]);
    }
}
