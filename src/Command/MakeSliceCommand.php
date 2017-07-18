<?php

namespace Kitty\AppSlice\Command;

use Illuminate\Console\Command;
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
    protected $description = 'Command description';

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

        $name = $this->ask(iconv("UTF-8", "GBK", "请输入要创建的应用名称"));
        $ob = new FileFactory($name);

        echo iconv("UTF-8", "GBK", "正在创建构建策略...！\n\n");
        sleep(1);
        echo iconv("UTF-8", "GBK", "正在创建应用 {$name} ...！\n");
        $this->output->progressStart(6);
        while ($ob->buildeApp()) {
            usleep(500000);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        echo iconv("UTF-8", "GBK", "正在初始化配置...！\n\n");
        sleep(1);
        $this->info(iconv("UTF-8", "GBK", "应用构建完成 Y(^_^)Y "));
    }
}
