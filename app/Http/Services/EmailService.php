<?php

namespace App\Services;


class EmailService
{

    /**
     * 错误信息
     * @var
     */
    protected $error = [];

    /**
     * admin用户token
     * @var
     */
    protected $token;

    /**
     * Basic Auth key
     * @var
     */
    protected $key;

    /**
     * 邮箱服务key
     * @var string
     */
    protected $authKey = '13jCoTc:2YX2MdURUkwuR';

    /**
     * 邮件渠道
     * @var string
     */
    protected $chan = 'mailgun';

    /**
     * 根路径
     * dev,production,staging,testing
     * @return mixed
     */
    protected function baseUrl()
    {
        return 'http://dadaabc.microserv';
    }

    /**
     * token校验
     * @return string
     */
    protected function tokenValidityUrl()
    {
        return $this->baseUrl() . '/validity.token.admin.json?pretty';
    }

    /**
     * 信息接口token校验
     * @return string
     */
    protected function msgValidityUrl()
    {
        return $this->baseUrl() . '/message.validity.user.json?pretty';
    }

    /**
     * 发送邮箱
     * @return string
     */
    protected function sendEmailUrl()
    {
        return $this->baseUrl() . '/message.email.send?pretty';
    }

    /**
     * 创建模板
     * @return string
     */
    protected function createEmailTplUrl()
    {
        return $this->baseUrl() . '/tpl.email.create?pretty';
    }

    /**
     * 获取错误信息
     * @return array
     */
    public function getError()
    {
        //error_demo
//        $arr = [
//            "status" => 500,
//            "data" => [
//                "code" => 101007011,
//                "message" => "Email request failed"
//            ]
//        ];
        return $this->error;
    }

    /**
     * 构造发送邮件请求体
     * @param $params
     * @return array
     */
    public function buildMailBody($params)
    {
        return [
            'tplID' => $params['tplID'],
            'tplData' => array_get($params,'tplData',''),
            'email' => $params['email'],
            'chan' => $this->chan,
            'from' => array_get($params,'from','DaDaABC Recruitment <teacher_recruitment@dadaabc.com>'),
        ];
    }

    /**
     * 邮件参数校验
     * @param $params
     * @param $token
     * @return bool
     */
    public function checkParams($params, $token)
    {
        if(empty(array_get($params,'tplID','')) || empty(array_get($params,'email','')) ){
            $this->error = 'EMAIL_REQUEST_ARHUMENT_ERROR';
            return false;
        }
        $this->token = $token;
        return true;
    }

    /**
     * 邮件发送请求
     * @param $params
     * @return bool|mixed
     */
    protected function sendRequest($params)
    {
        $postData = $this->buildMailBody($params);
        $dataString = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = $this->curlBasicAuth($this->sendEmailUrl(),$dataString,$this->authKey);
        if(!$res){
            //请求失败
            $this->error = 'EMAIL_REQUEST_MISSED';
            return false;
        }
        if($res['http_code']!=200){
            $this->error = 'EMAIL_REQUEST_FAILED';
            return false;
        }
        return $res['taskNum'];
    }

    /**
     * 发送邮件
     * @param $params
     * @return bool|mixed
     */
    public function send($params,$token='')
    {
        //邮件参数校验
        if( !$this->checkParams($params,$token) ){
            return false;
        }

        //身份校验失败
        if( !$this->messageValidity() ){
            return false;
        }

        return $this->sendRequest($params);
    }

    /**
     * 延迟发送
     * @param $params
     * @param int $time     将要发送的时间戳
     * @return bool
     */
    public function delaySend($params, int $time)
    {
        //todo 延迟发送
        $postData = $this->buildMailBody($params);
        $postData = array_merge($postData,['timing'=>$time]);
        $dataString = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = $this->curlBasicAuth($this->sendEmailUrl(),$dataString,$this->authKey);
        if(!$res){
            //请求失败
            $this->error = 'EMAIL_REQUEST_MISSED';
            return false;
        }
        if($res['http_code']!=200){
            $this->error = 'EMAIL_REQUEST_FAILED';
            return false;
        }
        return $res['taskNum'];
    }

    /**
     * 仅验证 basic auth user
     * @return bool
     */
    public function messageValidity()
    {
        $res = $this->curlUserValidity($this->msgValidityUrl(),$this->authKey);
        if(!$res){
            //请求失败
            $this->error = 'EMAIL_REQUEST_MISSED';
            return false;
        }
        if( array_get($res,'http_code')!=200 ){
            $this->error = 'EMAIL_USER_TOKEN_ERROR';
            return false;
        }

        return true;
    }

    public function curlBasicAuth($url, $data, $auth)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        curl_setopt($ch, CURLOPT_URL, $url);

        $output = curl_exec($ch);

        if ($output === false) {
            return false;
        }
        curl_close($ch);
        $return = json_decode($output,true);
        $return['http_code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        return $return;
    }

    public function curlUserValidity($url,$auth)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_setopt($ch, CURLOPT_URL, $url);

        $output = curl_exec($ch);

        if ($output === false) {
            return false;
        }
        curl_close($ch);
        $return = json_decode($output,true);
        $return['http_code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        return $return;
    }

    /**
     * 获取模板
     * @param $tplName
     * @param $email
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getTemplateId($tplName,$email)
    {
        $config = 'dadaEmail';
        if($this->checkGmail($email)){
            $type = 'base64';
        }else{
            $type = 'gmail';
        }

        return config($config . '.'. $type . '.' . $tplName . '.' . 'id');
    }

    /**
     * 判断是否是gmail邮箱
     * @param $email
     * @return bool
     */
    public function checkGmail($email)
    {
        $preg = '/@gmail\./';
        preg_match($preg,$email,$match);
        return empty($match);
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

    public function sendTemplate($fileList,$token,$prefix='gmail0-')
    {
        foreach($fileList as $item){
            //获取文件名
            $file_name = basename($item);
            //获取文件内容，去除换行
            $file_content = str_replace(PHP_EOL, '', file_get_contents($item));
            //从文件中获取变量
            $variable = $this->getVariable($file_content);

            $params = [
                'tag'       =>  $prefix.$file_name,
                'desc'      =>  $prefix.$file_name,
                'subject'   => 'Your application at DaDaABC',
                'content'   => $file_content
            ];
            //send
            $id = $this->buildBody($params,$token);
            if($id===false){
                \Log::info('gmail_tpl_failed_'.$file_name,['failed']);
            }
//            if($id['http_code']!=200){
//                \Log::info('gmail_tpl_error_'.$file_name,$id);
//            }
            //入库处理
            $this->saveMap($id['id'],$file_name,$variable);
        }
    }

    //获取变量
    public function getVariable($str)
    {
        $preg = '/\{{2}\.(?=[a-zA-Z]).*?\}{2}/';
        preg_match_all($preg,$str,$array);
        return $array;
    }

    public function buildBody($params, $token)
    {
        //todo send
        $header[] = 'Authorization: ' . $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($params,JSON_UNESCAPED_UNICODE));

        curl_setopt($ch, CURLOPT_URL, $this->createEmailTplUrl());

        $output = curl_exec($ch);

        if ($output === false) {
            return false;
        }
        curl_close($ch);
        $return = json_decode($output,true);
//        $return['http_code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        return $return;
    }

    //保存映射关系及其变量
    public function saveMap($id,$file_name,$variable)
    {
        $import = [
            'id' => $id,
            'file_name' => $file_name,
            'variable' => $variable
        ];
        $str = json_encode($import,JSON_UNESCAPED_UNICODE).PHP_EOL;
        // id,文件名，映射变量
        \Log::info('gmail_tpl_'.$file_name,$import);
    }
}