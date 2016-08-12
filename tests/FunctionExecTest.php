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
        $this->assertEquals(testFuncNoParam(), ['ok']);
    }

    public function testValidUserFuncCallByParam()
    {
        $proc = new Proc('testFuncParam', 'Prob\\Handler');
        $this->assertEquals(testFuncParam('param!'), ['param!']);
    }

    public function testInvalidFuncCall()
    {
        $this->expectException(NoFunctionException::class);
        $proc = new Proc('noFunc');
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
