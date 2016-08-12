<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;

class MethodExecTest extends TestCase
{
    public function testValidMethodCallByNoParam()
    {
        $proc = new Proc('MethodTestClass.testGetMethod', 'Prob\\Handler');
        $this->assertEquals($proc->exec(), 'ok');
    }

    public function testValidMethodCallByParam()
    {
        $proc = new Proc('MethodTestClass.testGetMethodParam', 'Prob\\Handler');
        $this->assertEquals($proc->exec('param!'), ['param!']);
    }

    public function testInvalidClassCall()
    {
        $this->expectException(NoClassException::class);
        $proc = new Proc('NoTestClass.testGetMethodParam', 'Prob\\Handler');
    }

    public function testInvalidMethodCall()
    {
        $this->expectException(NoMethodException::class);
        $proc = new Proc('MethodTestClass.NotestGetMethodParam', 'Prob\\Handler');
    }
}

class MethodTestClass
{
    private $str = '';

    public function __construct()
    {
        $this->str = 'ok';
    }

    public function testGetMethodParam($str)
    {
        return [$str];
    }

    public function testGetMethod()
    {
        return $this->str;
    }
}
