<?php

namespace App\Console\Commands;

use App\Models\TeacherJobTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class testEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testEmail:before';

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
        //1523978796
//        dd(date('Y-m-d H:i:s',1524046020));
        $search = time();
        //1524025680
        dd(date('Y-m-d H:i:s'));
        $now = strtotime('2018-04-18 12:28:00') + 8*3600;
        dd($now);
//        dd(date('Y-m-d H:i:s',1523978796));
        $this->remindEmailSend();
//        test 1523978796
    }

    public function remindEmailSend()
    {
        $time = time()+3600;
        $time = 1524046020-3600;
        $tasks = TeacherJobTask::select([
            'apply_user_email',
            'apply_user_name',
            'interview_start_time',
            'apply_id',
        ])
            ->where('step_id',3)
            ->where('stage_id',1)
            ->where('step_start_time','>=',$time)
            ->get();

        $url = 'www.baidu.com';
//        $emailService = new EmailService();
        //前台登录service
//        $loginService = new LoginService();
        $emails = [];
        foreach($tasks as $item){
            if(in_array($item->apply_user_email,$emails)){
                continue;
            }
            $cacheKey = $this->genKey($item->apply_user_email);
            $u = Redis::get($cacheKey);
            if($u){
                dump('has_end_'.$item->apply_user_email);
                continue;
            }
            Redis::setex($cacheKey,30,$item->apply_user_email);
            $emails[] = $item->apply_id;
            $token = 'token';
            $params = [
                'email'=>  'applicant',
                'tplID'=>  1937,
                'tplData'=>  [
                    'name'=>$item->apply_user_name,
                    'time'=>$item->interview_start_time,
                    'url'=>$url.$token
                ],
            ];
            \Log::info('testEmail_success',$params);
            dump($params);
//            $emailService->send($params);
        }
        \Log::info('testEmail_end',['end']);
    }

    public function genKey($email)
    {
        $key = 'sendReminder_';
        $key .= strtotime(date('Y-m-d')).'_';
        $key .= $email;
        return $key;
    }

    public function addEx($email)
    {
        Redis::setex($key,30,$email);
    }
}
