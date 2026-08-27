<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Generators\Commands\GenerateScaffoldCommand;
use App\Generators\Commands\RevertScaffoldCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateScaffoldCommand::class,
                RevertScaffoldCommand::class,
            ]);
        }
    }
}
