<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoFunctionException;

class FunctionExecTest extends TestCase
{
    public function testValidStdFuncCallByNoParam()
    {
        $proc = new Proc('pi');
        $this->assertEquals(\pi(), $proc->exec());
    }

    public function testValidStdFuncCallByParam()
    {
        $proc = new Proc('abs');
        $this->assertEquals(\abs(-17), $proc->exec(-17));
    }

    public function testValidUserFuncCallByNoParam()
    {
        $proc = new Proc('testFuncNoParam', 'Prob\\Handler');
        $this->assertEquals(testFuncNoParam(), $proc->exec());
    }

    public function testValidUserFuncCallByParam()
    {
        $proc = new Proc('testFuncParam', 'Prob\\Handler');
        $this->assertEquals(testFuncParam('param!'), $proc->exec('param!'));
    }

    public function testInvalidFuncCall()
    {
        $this->expectException(NoFunctionException::class);
        $proc = new Proc('noFunc');
    }

    public function testClosureCall()
    {
        $func = function ($str) {
            return $str.'!!!';
        };
        $proc = new Proc($func);
        $this->assertEquals($func('ok'), $proc->exec('ok'));
    }
}

function testFuncNoParam()
{
    return ['ok'];
}

function testFuncParam($str)
{
    return [$str];
}
