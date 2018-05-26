<?php

namespace Kitty\AppSlice;

use Illuminate\Support\ServiceProvider;
use Kitty\AppSlice\Command\GetVersionCommand;
use Kitty\AppSlice\Command\MakeControllerCommand;
use Kitty\AppSlice\Command\MakeModelCommand;
use Kitty\AppSlice\Command\MakeSliceCommand;
use Illuminate\Support\Facades\Route;
use Kitty\AppSlice\Operation\FileFactory;
use Kitty\AppSlice\Operation\HelperClass;

class AppSliceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $core_name;
    protected $space;
    protected $core_path;
    protected $package_path = 'vendor/kitty/appslice';

    public function boot()
    {
        require(base_path($this->package_path . '/src/functions/functions.php'));
        $fileFactory = new FileFactory();
        $this->core_name = $fileFactory->getCoreName();
        $this->core_path = $fileFactory->getCorePath();
        $this->space = $fileFactory->getSpace();
        $this->mapAppRoutes();
        $this->publishes([
            __DIR__ . '/Config/slice.php' => config_path('slice.php'),
        ]);
        $view_config = config('view.paths');
        $view_config[] = base_path();
        if ($dir_paths = (read_dir($this->core_path . '/' . $this->core_name))) {
            foreach ($dir_paths as $dir_name => $dir_path) {
                $view_config[] = $dir_path . '/Views';
            }
        }
        config(['view.paths' => $view_config]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.app.slice.make', function () {
            return new MakeSliceCommand();
        });
        $this->app->singleton('command.app.slice.version', function () {
            return new GetVersionCommand();
        });

        $this->app->singleton('command.app.slice.make.c', function () {
            return new MakeControllerCommand((new HelperClass()), (new FileFactory()));
        });

        $this->app->singleton('command.app.slice.make.m', function () {
            return new MakeModelCommand((new HelperClass()), (new FileFactory()));
        });
        $this->commands('command.app.slice.make');
        $this->commands('command.app.slice.version');
        $this->commands('command.app.slice.make.c');
        $this->commands('command.app.slice.make.m');

    }

    protected function mapAppRoutes()
    {
        if ($dir_paths = (read_dir($this->core_path . '/' . $this->core_name))) {
            $core_name = ucfirst(strtolower($this->core_name));
            foreach ($dir_paths as $dir_name => $dir_path) {
                if (file_exists($route_path = $dir_path . '/route.php')) {
                    $app_name = strtolower($dir_name);
                    $prefix = config('slice.app.' . $app_name . '.name', $app_name);
                    $middlewares_all = config('slice.app.all.middleware', []);
                    $middlewares_app = config('slice.app.' . $app_name . '.middleware', []);
                    $middlewares = array_merge($middlewares_all, $middlewares_app);
                    Route::group([
                        'middleware' => $middlewares,
                        'namespace' => $this->space . "\\$core_name\\$dir_name\\Controllers",
                        'prefix' => $prefix,
                    ], function ($router) use ($route_path) {
                        require $route_path;
                    });
                }
            }
        }
    }
}
