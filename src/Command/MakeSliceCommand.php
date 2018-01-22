<?php

namespace Kitty\AppSlice\Command;

use Illuminate\Console\Command;
use Kitty\AppSlice\HelperClass\HelperClass;
use Kitty\AppSlice\Operation\FileFactory;

class MakeSliceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:app {name=Demo} {--namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an App!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $str=file_get_contents(config_path('view.php'));
        $helper=new HelperClass();
        $location=$helper->strEndPlace($str,"'paths' => [\n");
        $res=$helper->insertToStr($str,$location,"\t\taaa\n");

        $name = $this->ask($helper->out( "请输入要创建的应用名称"));
        $ob = new FileFactory($name);

        $this->comment( $helper->out("正在创建构建策略...！"));
        sleep(1);
        $this->comment( $helper->out("正在创建应用 {$name} ...！"));
        $this->output->progressStart(8);
        while ($ob->buildeApp()) {
            usleep(500000);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this-> info($helper->out("正在初始化配置...！") );
        sleep(1);
        $this->info($helper->out("应用构建完成！"));
    }
}
