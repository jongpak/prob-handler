<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;

class MethodExecByParameterMapTest extends TestCase
{
    public function testMethodCall()
    {
        $bindClass = new ParameterInMethod();
        $bindClass->setStr('str');

        $map = new ParameterMap();
        $map->bindByName('arg1', '50');
        $map->bindByType('array', ['a', 'b', 'c']);
        $map->bindByNameWithType(ParameterInMethod::class, 'arg3', $bindClass);

        $proc = new Proc('MethodClass.glueArgs', 'Prob\\Handler');

        $this->assertEquals('50a,b,cstr', $proc->execWithParameterMap($map));
    }
}

class MethodClass
{
    public function glueArgs($arg1, array $arg2, ParameterInMethod $arg3)
    {
        return $arg1 . implode(',', $arg2) . $arg3->getStr();
    }
}

class ParameterInMethod
{
    private $str;

    public function setStr($str)
    {
        $this->str = $str;
    }

    public function getStr()
    {
        return $this->str;
    }
}
