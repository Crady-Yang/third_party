<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Re

class redisTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->test();
        $this->assertTrue(true);
    }

    public function test()
    {
        $data = [
            '15021004529@163.com',
            '139000@163.com',
        ];
        Redis::set('keyas','name');
//        Redis::setex('key',30,$data);
    }
}
