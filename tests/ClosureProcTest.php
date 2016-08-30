<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc\ClosureProc;
use Prob\Handler\ParameterMap;

class ClosureProcTest extends TestCase
{

    public function testNamespace()
    {
        $proc = new ClosureProc(function () { });
        $this->assertEquals(null, $proc->getNamespace());
    }

    public function testName()
    {
        $proc = new ClosureProc(function () { });
        $this->assertEquals('{closure}', $proc->getName());
    }

    public function testCallNoArgument()
    {
        $func = function () {
            return 'call no argument';
        };
        $proc = new ClosureProc($func);
        $this->assertEquals($func(), $proc->exec());
    }

    public function testCallArguments()
    {
        $func = function ($num1, $num2, $num3) {
            return $num1 * $num2 * $num3;
        };
        $proc = new ClosureProc($func);
        $this->assertEquals($func(5, 10, 0.5), $proc->exec(5, 10, 0.5));
    }

    public function testCallParameterMap()
    {
        $func = function ($num1, $num2, $num3) {
            return $num1 * $num2 * $num3;
        };
        $proc = new ClosureProc($func);

        $parameterMap = new ParameterMap();
        $parameterMap->bindByName('num3', 0.5);
        $parameterMap->bindByName('num2', 10);
        $parameterMap->bindByName('num1', 5);

        $this->assertEquals($func(5, 10, 0.5), $proc->execWithParameterMap($parameterMap));
    }
}
