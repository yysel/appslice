<?php

namespace Kitty\AppSlice\Command;

use Illuminate\Console\Command;
use Kitty\AppSlice\Operation\HelperClass;
use Kitty\AppSlice\Operation\FileFactory;

class MakeModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     * --r 是否创建资源路由
     * @var string
     */
    protected $signature = 'make:m {name?} {--app=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an Model on one App!';

    protected $helper;

    protected $factory;

    protected $model;

    protected $app;

    protected $core_name;

    protected $namespace;
    protected $core_path;

    protected $app_path = [];

    protected $app_display_path = [];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HelperClass $helper, FileFactory $factory)
    {
        parent::__construct();
        $this->helper = $helper;
        $this->factory = $factory;
        $this->core_name = $factory->getCoreName();
        $this->core_path = $factory->getCorePath();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $app_path = $this->checkName()->checkApp();
        $this->factory->makeModelFile($app_path, $this->model, $this->namespace);
        $this->info($this->helper->out("模型创建成功！【{$app_path}】"));
        exit;
    }


    protected function checkName()
    {
        $name = $this->argument('name');
        $this->model = $name;
        if (!$name) $this->model = $this->ask($this->helper->out("请输入模型的名称"));
        return $this;
    }

    protected function checkApp()
    {
        $app = $this->option('app');
        $app_tag = $this->readApp();
        if (!$app_tag) {
            $this->error($this->helper->out("没有发现应用！请运行 php artisan make:app 创建一个应用"));
            exit;
        }
        if (!$app) {
            $choice = $this->choice("您要在哪个应用里创建，请选择？", $app_tag);
            $this->namespace = ucfirst(strtolower($this->core_name)) . '\\' . str_replace('/', '\\', $choice);
            return $this->app_path[$choice];
        }
        $this->namespace = ucfirst(strtolower($this->core_name)) . '\\' . ucfirst(strtolower( $app)).'\\Models';
        return rtrim($this->core_path, '/') . '/' . $this->core_name . '/' .ucfirst(strtolower( $app)).'/Models';
    }

    protected function readApp()
    {
        $core_full_path = $this->core_path . '/' . $this->core_name;
        $apps = read_dir($core_full_path);
        foreach ($apps as $key => $app) {
            $controller_path = $app . '/Models';
            $display_path = $key . '/Models';
            $this->app_path[$display_path] = $controller_path;
            $this->app_display_path[] = $display_path;
        }
        return $this->app_display_path;
    }
}
