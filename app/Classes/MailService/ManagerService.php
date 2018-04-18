<?php

namespace App\Classes\MailService;

/**
 * Created by PhpStorm.
 * User: crady_yang
 * Date: 2018/4/10
 * Time: 下午2:38
 */

class ManagerService
{
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

    public function sendTemplate($fileList)
    {
        $prefix = 'test0-';
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
            $id = $this->buildBody($params);
            //入库处理
            $this->saveMap($id,$file_name,$variable);
        }
    }

    //获取变量
    public function getVariable($str)
    {
        $preg = '/\{{2}\.(?=[a-zA-Z]).*?\}{2}/';
        preg_match_all($preg,$str,$array);
        return $array;
    }

    public function buildBody($params)
    {
        //todo send
        return rand(1000,9999);
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
        file_put_contents('/Users/crady_yang/myProjects/php/third_party/1.log',$str,FILE_APPEND);
    }
}