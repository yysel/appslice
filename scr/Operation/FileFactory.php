<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 2017-07-15
 * Time: 20:08
 */

namespace Kitty\AppSlice\Operation;


class FileFactory
{
    protected $core_base_path;
    protected $core_name;
    protected $core_path;
    protected $app_path;
    protected $app_name;
    protected $file_contents;
    protected $count = 0;


    public function __construct($app_name = '')
    {
        $this->app_name = ucfirst(strtolower($app_name));
        $this->core_name = ucfirst(strtolower(config('slice.core.name', 'Core')));
        $this->core_base_path = config('slice.core.path', base_path());
        $this->core_path = $this->core_base_path . '/' . $this->core_name;
        $this->file_contents = new FillContent();
    }

    public function makeDirIfNotExist($path, $model = '0777', $r = true)
    {
        if (!is_dir($path)) return mkdir($path, $model, $r);
        return false;
    }

    protected function getAppDirPath()
    {
        return $app_dir = $this->core_path . '/' . $this->app_name;
    }

    public function makeControllerDir()
    {
        $app_dir = $this->getAppDirPath();

        return $this->makeDirIfNotExist($app_dir . '/Controllers');
    }

    public function makeViewDir()
    {
        $app_dir = $this->getAppDirPath();

        return $this->makeDirIfNotExist($app_dir . '/Views');
    }


    public function makeRouteFile()
    {
        $app_dir = $this->getAppDirPath();
        $route = $this->makeFileIfNotExsit($app_dir . '/route.php');
        $content = "<?php\n\n\nRoute::get('/','DemoController@demo');";
        return fwrite($route, $content);
    }

    public function makeDemoControllerFile()
    {
        $app_dir = $this->getAppDirPath();
        $controller = $this->makeFileIfNotExsit($app_dir . '/Controllers/DemoController.php');
        $content = $this->file_contents::DemoController;
        $content = strtr($content, ['{Core}' => $this->core_name, '{App}' => $this->app_name]);
        return fwrite($controller, $content);
    }

    public function makeDemoViewFile()
    {
        $app_dir = $this->getAppDirPath();
        $view = $this->makeFileIfNotExsit($app_dir . '/Views/demo.blade.php');
        $content = "欢迎来到应用{$this->app_name}的首页";
        return fwrite($view, $content);
    }

    protected function makeFileIfNotExsit($name)
    {
//        $mode = file_exists($name) ? 'a' : 'w';

        return $file = fopen($name, $mode = 'w');
    }

    public function buildeApp()
    {
        switch ($this->count) {
            case 0:
                $this->makeControllerDir();
                return ++$this->count;
                break;
            case 1:
                $this->makeDemoControllerFile();
                return ++$this->count;
                break;
            case 2:
                $this->makeViewDir();
                return ++$this->count;
                break;
            case 3:
                $this->makeDemoViewFile();
                return ++$this->count;
                break;
            case 4:
                $this->makeRouteFile();
                return $this->count = 0;
                break;
        }
    }
}