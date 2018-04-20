<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class createEmailTpl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailTemplate:create {type} {path}';

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
        //
        $type  = $this->argument('type');
        $path  = $this->argument('path');
        if($type=='file'){
            /**
             * demo
             * php artisan emailTemplate:create file /Users/crady_yang/myProjects/php/third_party/public/emailGmail/offer/offer.html,/Users/crady_yang/myProjects/php/third_party/public/emailGmail/Interview/interviewSuccess.html
             */
            $list = $this->file($path);
        }else{
            /**
             * demo
             * php artisan emailTemplate:create path /Users/crady_yang/myProjects/php/third_party/public/emailGmail
             */
            $list = $this->test($path);
        }

        $service = new EmailService();
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1X2lkIjoiNDY4IiwidV9taXgiOiJjcHRhMjE1NSIsInVfbmFtZSI6InNwZW5jZXIiLCJ1X3RpbWUiOjE1MjMxNTE1NzZ9.T_pQRk2kWhPk7bfxL_GXx6ZKr2E0CYMXR6SotQpB9nw';
        $service->sendTemplate($list,$token,'base64');
    }

    public function test($path)
    {
        $service = new EmailService();
        $list = $service->get_all_files($path);
        return $list;
    }

    public function file($files)
    {
        $files = explode(',',$files);
        return $files;
    }
}
