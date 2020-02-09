<?php

namespace LaPress\Image;

use Illuminate\Support\ServiceProvider;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ImageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->publishes([
            __DIR__.'/../config/images.php' => config_path('images.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/images.php', 'images'
        );
    }
}
