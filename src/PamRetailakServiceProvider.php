<?php

namespace pantech\retailak;

use Illuminate\Support\ServiceProvider;

class PamRetailakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/views' => base_path('resources'),
            __DIR__.'/migrations' => base_path('database'),
            __DIR__.'/Console' => base_path('app'),
            __DIR__.'/Events' => base_path('app'),
            __DIR__.'/Exceptions' => base_path('app'),
            __DIR__.'/Filters' => base_path('app'),
            __DIR__.'/Http' => base_path('app'),
            __DIR__.'/Imports' => base_path('app'),
            __DIR__.'/Listeners' => base_path('app'),
            __DIR__.'/Mail' => base_path('app'),
            __DIR__.'/Models' => base_path('app'),
            __DIR__.'/Scopes' => base_path('app'),
        ]);
    }
}
