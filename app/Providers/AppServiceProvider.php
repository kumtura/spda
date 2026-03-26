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

            // Create a dummy request if running in CLI to prevent crash
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
        $settingsPath = storage_path('app/settings.json');
        $village = [];
        if (file_exists($settingsPath)) {
            $village = json_decode(file_get_contents($settingsPath), true);
        }
        \Illuminate\Support\Facades\View::share('village', $village);

        // Share base layout based on role
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $level = session('level');
            $layout = in_array($level, [1, 4]) ? 'index' : 'mobile_layout';
            $view->with('base_layout', $layout);
        });

        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
