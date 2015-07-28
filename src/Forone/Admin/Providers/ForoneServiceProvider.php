<?php
namespace Forone\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class ForoneServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'forone');
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/../routes.php';
        }

        $this->registerPublish();
    }

    private function registerPublish()
    {
        // publish views
        $this->publishes([
            __DIR__.'/../../../resources/views' => base_path('resources/views/vendor/foreone'),
        ]);

        // publish config
        $this->publishes([
            __DIR__.'/../../../config/config.php' => config_path('forone.php'),
        ]);

        // publish assets to public/
        $this->publishes([
            __DIR__.'/../../../../public' => public_path('vendor/forone'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
