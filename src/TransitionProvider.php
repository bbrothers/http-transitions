<?php

namespace Transitions;

use Illuminate\Support\ServiceProvider;

/**
 * Class TransitionProvider
 * @package Transitions
 */
class TransitionProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('transitions.php'),
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'transitions');

        $this->app->bind(TransitionMiddleware::class, function ($app) {
            $config = new Config($app['config']->get('transitions'));
            return new TransitionMiddleware($config, new TransitionFactory($app));
        });
    }
}
