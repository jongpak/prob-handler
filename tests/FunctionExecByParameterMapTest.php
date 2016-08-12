<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;

class FunctionExecByParameterMapTest extends TestCase
{
    public function testFuncCall()
    {
        $bindClass = new ParameterInFunction();
        $bindClass->setStr('str');

        $map = new ParameterMap();
        $map->bindByName('arg1', '50');
        $map->bindByType('array', ['a', 'b', 'c']);
        $map->bindByNameWithType(ParameterInFunction::class, 'arg3', $bindClass);

        $proc = new Proc('Prob\\Handler\\glueArgs');

        $this->assertEquals($proc->execWithParameterMap($map), '50a,b,cstr');
    }
}

function glueArgs($arg1, array $arg2, ParameterInFunction $arg3)
{
    return $arg1 . implode(',', $arg2) . $arg3->getStr();
}

class ParameterInFunction
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
