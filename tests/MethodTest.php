<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;

class MethodTest extends TestCase
{
    public function testValidMethodCallByNoParam()
    {
        $proc = new Proc('TestClass.testGetMethod', 'Prob\\Handler');
        $this->assertEquals($proc->exec(), 'ok');
    }

    public function testValidMethodCallByParam()
    {
        $proc = new Proc('TestClass.testGetMethodParam', 'Prob\\Handler');
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
        $proc = new Proc('TestClass.NotestGetMethodParam', 'Prob\\Handler');
    }
}

class TestClass
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