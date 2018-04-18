<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class replaceEmailTpl extends Command
{

    protected $name = 'emailTemplate:replace';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailTemplate:replace {type} {reqnbr?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '邮件模板替换脚本 调用类型(type|)';

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
        //
        $type   = $this->argument('type');
        $reqnbr = $this->argument('reqnbr');

    }

    /**
     * 遍历文件夹
     */
    function get_all_files( $path ){
        $list = array();
        foreach( glob( $path . '/*') as $item ){
            if( is_dir( $item ) ){
                $list = array_merge( $list , $this->get_all_files( $item ) );
            }
            else{
                $list[] = $item;
            }
        }
        return $list;
    }
}
