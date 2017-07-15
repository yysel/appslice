<?php

namespace Kitty\AppSlice;

use Illuminate\Support\ServiceProvider;
use Kitty\AppSlice\Command\MakeSliceCommand;
use Kitty\AppSlice\Operation\FileFactory;
use Illuminate\Support\Facades\Route;

class AppSliceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $core_name;
    protected $package_path = 'packages/kitty/appslice/scr/functions';

    public function boot()
    {
        require(base_path($this->package_path . '/functions.php'));
        $this->core_name = ucfirst(strtolower(config('slice.core.name', 'Core')));
        $this->mapAppRoutes();
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
        $ob= new FileFactory('admin');
//        dd($ob->buildeApp());
    }

    protected function mapAppRoutes()
    {
        if ($dir_paths = (read_dir(base_path($this->core_name)))) {
            foreach ($dir_paths as $dir_name => $dir_path) {
                if (file_exists($route_path = $dir_path . '/route.php')) {
                    Route::prefix(strtolower($dir_name))
                        ->namespace("{$this->core_name}\\$dir_name\\Controllers")
                        ->group($route_path);
                }
            }
        }
    }
}
