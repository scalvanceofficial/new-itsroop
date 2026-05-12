<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/backend.php'));
        });

        $this->registerRouteBinding(app_path() . DS . 'Models', 'App\\Models\\');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * @return mixed
     */
    private function registerRouteBinding($path, $namespace)
    {
        $classes = scandir($path);

        foreach ($classes as $idx => $class) {
            if (strpos($class, '.') === 0) {
                continue;
            }
            if (is_dir($path . DS . $class)) {
                $this->registerRouteBinding($path . DS . $class, $namespace . $class . '\\');
                continue;
            }
            if (!ends_with($class, '.php')) {
                continue;
            }

            $key = snake_case(basename($class, '.php'));
            $classname = $namespace . basename($class, '.php');

            try {
                Route::bind($key, function ($value) use ($classname, $key) {
                    \Log::info('Route::bind triggered', ['key' => $key, 'value' => $value, 'classname' => $classname]);
                    return $this->getModel($classname, $value);
                });
            } catch (\Exception $e) {
                // Do nothing
            }
        }
    }

    private function getModel($class, $routeKey)
    {
        $model = "App\\Models\\" . basename($class, '.php');
        \Log::info('getModel attempting resolution', ['model' => $model, 'routeKey' => $routeKey]);

        if (!is_numeric($routeKey)) {
            $decoded = \Hashids::connection($model)->decode($routeKey);
            $routeKey = $decoded[0] ?? null;
            \Log::info('getModel decoded hashid', ['decoded' => $decoded, 'finalKey' => $routeKey]);
        }

        if ($routeKey) {
            $resolved = $model::findOrFail($routeKey);
            \Log::info('getModel resolved successfully', ['id' => $resolved->id]);
            return $resolved;
        }

        \Log::warning('getModel failed to resolve', ['model' => $model, 'routeKey' => $routeKey]);
        return abort(404);
    }
}