<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc\FunctionProc;
use Prob\Handler\ParameterMap;
use Prob\Handler\Exception\NoFunctionException;

class FunctionProcTest extends TestCase
{
    public function setUp()
    {
        require_once 'mock/function.php';
    }

    public function testNoFunctionException()
    {
        $this->expectException(NoFunctionException::class);
        $proc = new FunctionProc('???');
    }

    public function testNamespace()
    {
        $proc = new FunctionProc('functionNoArgument', 'Prob\\Handler\\Test\\Functions');
        $this->assertEquals('Prob\\Handler\\Test\\Functions', $proc->getNamespace());
    }

    public function testName()
    {
        $proc = new FunctionProc('functionNoArgument', 'Prob\\Handler\\Test\\Functions');
        $this->assertEquals('functionNoArgument', $proc->getName());
    }

    public function testCallNoArgument()
    {
        $proc = new FunctionProc('functionNoArgument', 'Prob\\Handler\\Test\\Functions');
        $this->assertEquals(
                            Test\Functions\functionNoArgument(),
                            $proc->exec()
        );
    }

    public function testCallArguments()
    {
        $proc = new FunctionProc('functionArguments', 'Prob\\Handler\\Test\\Functions');
        $this->assertEquals(
                            Test\Functions\functionArguments(5, 10, 0.5),
                            $proc->exec(5, 10, 0.5)
        );
    }

    public function testCallParameterMap()
    {
        $proc = new FunctionProc('functionArguments', 'Prob\\Handler\\Test\\Functions');

        $parameterMap = new ParameterMap();
        $parameterMap->bindByName('num3', 0.5);
        $parameterMap->bindByName('num2', 10);
        $parameterMap->bindByName('num1', 5);

        $this->assertEquals(
                            Test\Functions\functionArguments(5, 10, 0.5),
                            $proc->execWithParameterMap($parameterMap)
        );
    }
}
