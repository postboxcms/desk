<?php

namespace PostboxCMS\Desk;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use PostboxCMS\Desk\Console\AddCommand;
use PostboxCMS\Desk\Console\CreateUserCommand;
use PostboxCMS\Desk\Console\InstallCommand;
use PostboxCMS\Desk\Console\PublishCommand;

class DeskServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->configurePublishing();
    }

    /**
     * Register the console commands for the package.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                AddCommand::class,
                PublishCommand::class,
                CreateUserCommand::class
            ]);
        }
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../runtimes' => $this->app->basePath('docker'),
            ], ['desk', 'desk-docker']);

            $this->publishes([
                __DIR__ . '/../bin/desk' => $this->app->basePath('desk'),
            ], ['desk', 'desk-bin']);

            $this->publishes([
                __DIR__ . '/../database' => $this->app->basePath('docker'),
            ], ['desk', 'desk-database']);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            InstallCommand::class,
            PublishCommand::class,
        ];
    }
}
