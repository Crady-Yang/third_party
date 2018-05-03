<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Created by PhpStorm.
 * User: crady_yang
 * Date: 2018/4/12
 * Time: 下午5:25
 */

class pregTest extends TestCase
{
    public function testBasicTest()
    {
        $str = '1231231231<asd>asdas<213></123>das</asd>23123123';
        $u = $this->t($str);
        dd($u);
        $this->test();
        $this->pregReplace();dd(1);
        $u = $this->testPreg();
        dd($u);
        $path = '/Users/crady_yang/myProjects/php/third_party/public/emailProject/email';

        $fileList = $this->get_all_files($path);dd($fileList);
        $rs = $this->updateHtml($fileList);
        dd($rs);
        $this->assertTrue(true);
    }

    public function testPreg()
    {
        $path = '/Users/crady_yang/myProjects/php/third_party/public/emailProject/email/ personnalInfo/approvedPersonalInfo.html';
        $file_content = file_get_contents($path);
        return $this->getVariable($file_content);
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

    public function updateHtml($fileList)
    {
        foreach($fileList as $item){
            //获取文件名
            $file_name = basename($item);
            //获取文件内容
            $file_content = file_get_contents($item);
            //从文件中获取变量
            $baseMatches = $this->getBase64($file_content);
            if(empty($baseMatches)){
                continue;
            }
            $newStr = $this->replaceStr($file_content,$baseMatches);
            file_put_contents($item,$newStr);
        }
        return true;
    }

    //获取变量
    public function getVariable($str)
    {
        $preg = '/\{{2}\.(?=[a-zA-Z]).*?\}{2}/';
        preg_match_all($preg,$str,$array);
        return $array;
    }

    public function getBase64($str)
    {
        $preg = '/\"data\:image\/png\;.*?\"{1}/';
        preg_match_all($preg,$str,$matches);
        return $matches[0];
    }

    public function replaceStr($str,$matches)
    {
        $title = '"https://drive.google.com/uc?export=view&id=';
        $arr = [
            $title . '1-ejZu2n4rJ8lWKaNnKKSvQC3Mhhzn-3P' . '"',
            $title . '1voemrt0mm5g4XeXSUgT65fTohOcV-1Ya' . '"',
            $title . '1TrmudC332xh9qE27kF7XS2t56hB8OzWO' . '"',
            $title . '1qaX0bNZIDMJLwdVI0lR8YNkjrCow48sq' . '"',
            $title . '1Qwr1wlrfFoeXbonl5Bx4Kmn6LaI7FkgH' . '"',
            $title . '18xHvT766SZAJrPretVoS8WkPF3Tp6HB-' . '"',
            $title . '1790yVzMhXNYwtrG_TYWSyy1OKvY9EXh2' . '"',
            $title . '15KRUdCK-Smj_mbXnWidQ_Vh7aPJKRfDy' . '"',
        ];

        $newStr = str_replace($matches,$arr,$str);
        return $newStr;
    }

    public function pregReplace()
    {
        $str = '123123base64:123",adasdbase64:213",12312base64:222"';
        $replace = [
            '--aa','--bb','--cc'
        ];
        $preg = '/base64\:.*?\"{1}/i';
        preg_match_all($preg,$str,$array);
        $u = str_replace(['a','b'],['b','c'],'abcs');
//        dd($array,$replace);
        $newStr = str_replace($array[0],$replace,$str);
        dd($newStr);
    }

    public function test()
    {
        $str = '12312312{{.host}}fad123{{.host123ew}}.';
        $preg = '/\{{2}\.host\}{2}/';
        $u = preg_replace($preg,'www.baidu.com',$str);
//        $preg = '/\{{2}\.(?=[host]).*?\}{2}/';
//        preg_match_all($preg,$str,$array);
        dd($u);
    }

    public function t($content)
    {
        $preg = '/\<.*?\>.*?\<\/.*\>/';
        preg_match($preg,$content,$matches);
        return $matches;
    }
}