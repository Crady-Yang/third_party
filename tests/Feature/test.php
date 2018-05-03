<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $a = [1,2,3];
        $n = [2,3,4];
        $rs1 = array_intersect($a,$n);
        $rs2 = array_diff($a,$n);
        $rs3 = array_diff($n,$a);
        dd($rs3,$rs2);
        $this->assertTrue(true);
    }
}

