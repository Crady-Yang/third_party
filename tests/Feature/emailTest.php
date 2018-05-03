<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class emailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        dd(in_array(false,[0,null],true));
        $input = [1,2,3,5];
        $origin = [1,2,3,4];
        dd(array_intersect($input,$origin));
        dd(in_array(1,$input));
        $u = $this->test($input,$origin);//添加
        $v = $this->tong($input,$origin);//删除
        dd($u,$v);
        $this->assertTrue(true);
    }

    public function getBase64()
    {
        $str = 'htwfmdkhtmlhtml<html><head><meta http-equiv=\"Content-Type\" content=\"text/html;</html>htmakld';
        $preg = '/\<html\>.*?\<\/html\>/';
        preg_match_all($preg,$str,$matches);
        return $matches[0];
    }

    public function test($input,$origin)
    {
        return array_values(array_diff($input,$origin));
    }

    public function tong($input,$origin)
    {
        return array_diff($origin,$input);
    }

    public function strReplace()
    {
        $str = 'About us, Contact us';
        $url = [
            'https://abin-www.dadaabc.us/apps/teacher_landing/privacy_statement.html',
            'https://abin-www.dadaabc.us/apps/teacher_landing/contact_us.html',
        ];
        $newStr = str_replace(['About us','Contact us'],$url,$str);dd($newStr);
        return $newStr;
    }
}
