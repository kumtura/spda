<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();
            $app->instance('routes', $routes);

            // Ensure we have a request instance, even if it's a dummy for CLI
            $request = $app->bound('request') ? $app['request'] : \Illuminate\Http\Request::create('/');

            return new \Illuminate\Routing\UrlGenerator(
                $routes, $request, $app['config']['app.asset_url']
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
