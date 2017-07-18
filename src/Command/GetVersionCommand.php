<?php

namespace Kitty\AppSlice\Command;

use Illuminate\Console\Command;

class GetVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slice';
    protected $version = "\nApp-Slice  version 1.2.2  2017-07-18 \n\n";

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
        echo $this->version;
    }
}
