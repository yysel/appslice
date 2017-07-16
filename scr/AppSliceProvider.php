<?php

namespace Kitty\AppSlice;

use Illuminate\Support\ServiceProvider;
use Kitty\AppSlice\Command\MakeSliceCommand;
use Illuminate\Support\Facades\Route;

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
        require(base_path($this->package_path . '/scr/functions/functions.php'));
        $this->core_name = ucfirst(strtolower(config('slice.core.name', 'Core')));
        $this->core_path = config('slice.core.path', base_path('app'));
        $this->space = ucfirst(strtolower(strtr($this->core_path, [base_path() => '', '/' => '', '\\' => ''])));
        $this->mapAppRoutes();
        $this->publishes([
            __DIR__ . '/Config/slice.php' => config_path('slice.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.app.slice', function () {
            return new MakeSliceCommand();
        });
        $this->commands('command.app.slice');

    }

    protected function mapAppRoutes()
    {
        if ($dir_paths = (read_dir($this->core_path . '/' . $this->core_name))) {
            foreach ($dir_paths as $dir_name => $dir_path) {
                if (file_exists($route_path = $dir_path . '/route.php')) {
                    Route::prefix(strtolower($dir_name))
                        ->namespace($this->space . "\\{$this->core_name}\\$dir_name\\Controllers")
                        ->group($route_path);
                }
            }
        }
    }
}
