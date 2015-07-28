<?php
namespace Forone\Admin\Providers;

use Artesaos\Defender\Providers\DefenderServiceProvider;
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
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/../routes.php';
        }

        $this->publishResources();

        $this->publishMigrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

//        $this->registerProvider();
//        $this->registerAlias();

        // Controle de acesso mais simples, utiliza apenas os grupos
        $this->app['router']->middleware('needsRole', \Artesaos\Defender\Middlewares\NeedsRoleMiddleware::class);
    }



    private function registerCommands()
    {
        $this->commands([
            \Forone\Admin\Console\ClearDatabase::class,
            \Forone\Admin\Console\InitCommand::class,
            \Forone\Admin\Console\Upgrade::class,
        ]);
    }

//    private function registerProvider()
//    {
//        $this->app->register(\Artesaos\Defender\Providers\DefenderServiceProvider::class);
//    }
//
//    private function registerAlias()
//    {
//        $this->app->alias('Defender', \Artesaos\Defender\Providers\DefenderServiceProvider::class);
//    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        // publish views
        $this->publishes([__DIR__.'/../../resources/views' => base_path('resources/views/vendor/foreone'),]);

        // publish config
        $this->publishes([__DIR__.'/../../config/config.php' => config_path('forone.php'),]);
        $this->publishes([__DIR__.'/../../config/auth.php' => config_path('auth.php'),]);

        // publish assets
        $this->publishes([__DIR__.'/../../../public' => public_path('vendor/forone'),], 'public');

        // To register your package's views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'forone');
    }

    /**
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes([__DIR__.'/../../migrations/' => base_path('database/migrations')], 'migrations');
    }
}
