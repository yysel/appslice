<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 2017-07-15
 * Time: 20:08
 */

namespace Kitty\AppSlice\Operation;


use Kitty\AppSlice\Operation\HelperClass;

class FileFactory
{
    protected $core_base_path;
    protected $core_name;
    protected $core_path;
    protected $app_path;
    protected $app_name;
    protected $space;
    protected $file_contents;
    protected $count = 0;


    public function __construct($app_name = '')
    {
        $this->oldumask = umask(0);
        $this->app_name = ucfirst(strtolower($app_name));
        $this->core_name = config('slice.core.name', 'core');
        $this->core_base_path = config('slice.core.path', base_path());
        $this->space = ucfirst(strtolower(strtr($this->core_base_path, [base_path() => '', '/' => '', '\\' => ''])));
        if ($this->space) $this->space = $this->space . '\\';
        $this->core_path = $this->core_base_path . '/' . $this->core_name;
        $this->file_contents = new FillContent();
        $this->helper = new  HelperClass();
    }

    public function makeDirIfNotExist($path, $model = 0755, $r = true)
    {
        if (!is_dir($path)) {
            return mkdir($path, $model, $r);
        }
        return false;
    }

    public function getCoreName()
    {
        return $this->core_name;
    }

    public function getCorePath()
    {
        return $this->core_base_path;
    }

    public function getSpace()
    {
        return $this->space;
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

    public function makeMiddlewareDir()
    {
        $app_dir = $this->getAppDirPath();

        return $this->makeDirIfNotExist($app_dir . '/Middleware');
    }

    public function makeModelsDir()
    {
        $app_dir = $this->getAppDirPath();

        return $this->makeDirIfNotExist($app_dir . '/Models');
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
        $core_name = ucfirst(strtolower($this->core_name));
        $namespace = $core_name . '\\' . $this->app_name . '\Controllers';
        $this->makeControllerFile($app_dir . '/Controllers', 'Demo', $namespace, 'DemoController');
    }

    public function makeControllerFile($path, $name, $namespace, $mode = 'Controller')
    {
        $name = ucfirst(strtolower($name));

        $controller = $this->checkDir($path)->makeFileIfNotExsit(rtrim($path) . '/' . $name . 'Controller.php');
        if ($mode === 'DemoController') $content = $this->file_contents::DemoController;
        elseif ($mode === 'ResourceController') $content = $this->file_contents::ResourceController;
        else $content = $this->file_contents::Controller;
        $content = strtr($content, ['{namespace}' => $namespace, '{name}' => $name, '{sname}' => strtolower($name)]);
        return fwrite($controller, $content);
    }

    public function makeModelFile($path, $name, $namespace)
    {
        $name = ucfirst($name);
        $model = $this->checkDir($path)->makeFileIfNotExsit(rtrim($path) . '/' . $name . '.php');
        $content = $this->file_contents::Model;
        $content = strtr($content, ['{namespace}' => $namespace, '{name}' => $name, '{sname}' => strtolower($name)]);
        return fwrite($model, $content);
    }


    public function checkDir($dir)
    {
        is_dir($dir) || die('目录：【' . $dir . '】 不存在！' . PHP_EOL);
        return $this;
    }

    public function makeDemoViewFile()
    {
        $app_dir = $this->getAppDirPath();
        $view = $this->makeFileIfNotExsit($app_dir . '/Views/demo.blade.php');
        $content = $this->file_contents::DemoView;
        $content = strtr($content, ['{title}' => $this->app_name . '的首页', '{App}' => $this->app_name]);
        return fwrite($view, $content);
    }

    protected function makeFileIfNotExsit($name)
    {
//        $mode = file_exists($name) ? 'a' : 'w';
        return $file = fopen($name, $mode = 'w');
    }

    public function updateComposer()
    {
        $composer = (json_decode(file_get_contents(base_path('composer.json')), true));
        $composer['autoload']['psr-4'][ucfirst(strtolower($this->core_name)) . '\\'] = $this->core_name . '/';
        $composer = json_encode($composer, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(base_path('composer.json'), $composer);
    }

    public function updateViewFile()
    {
        $view_path = config_path('view.php');
        $str = $res = file_get_contents($view_path);
        $helper = new HelperClass();
        $location = $helper->strEndPlace($str, "'paths' => [\n");
        $core = strpos($str, "base_path('core')");
        if (!$core) $res = $helper->insertToStr($str, $location, "\t\tbase_path('core'),\n");
        $app_name_str = "base_path('{$this->core_name}/{$this->app_name}/Views')";
        $app_name = strpos($str, $app_name_str);
        if (!$app_name) $res = $helper->insertToStr($res, $location, "\t\t{$app_name_str},\n");
        if ($str != $res) file_put_contents($view_path, $res);
    }

    public function buildApp()
    {
        switch ($this->count) {
            case 0:
                $this->makeControllerDir();
                return ++$this->count;
            case 1:
                $this->makeDemoControllerFile();
                return ++$this->count;
            case 2:
                $this->makeViewDir();
                return ++$this->count;
            case 3:
                $this->makeDemoViewFile();
                return ++$this->count;
            case 4:
                $this->makeMiddlewareDir();
                return ++$this->count;
            case 5:
                $this->makeModelsDir();
                return ++$this->count;
            case 6:
                $this->makeRouteFile();
                return ++$this->count;
            case 7:
                $this->updateComposer();
                return ++$this->count;
            case 8:
                $this->updateViewFile();
                return $this->count = -1;
        }
    }

    public function __destruct()
    {
        umask($this->oldumask);
    }
}