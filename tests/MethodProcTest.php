<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc\MethodProc;
use Prob\Handler\ParameterMap;

class MethodProcTest extends TestCase
{
    public function setUp()
    {
        require_once 'mock/method.php';
    }
    public function testNamespace()
    {
        $proc = new MethodProc('Test.methodNoArgument', 'Prob\\Handler\\Test\\Method');
        $this->assertEquals('Prob\\Handler\\Test\\Method', $proc->getNamespace());
    }

    public function testName()
    {
        $proc = new MethodProc('Test.methodNoArgument', 'Prob\\Handler\\Test\\Method');
        $this->assertEquals('Test.methodNoArgument', $proc->getName());
    }

    public function testCallNoArgument()
    {
        $proc = new MethodProc('Test.methodNoArgument', 'Prob\\Handler\\Test\\Method');

        $instance = new Test\Method\Test();
        $this->assertEquals(
                            $instance->methodNoArgument(),
                            $proc->exec()
        );
    }

    public function testCallArguments()
    {
        $proc = new MethodProc('Test.methodArguments', 'Prob\\Handler\\Test\\Method');

        $instance = new Test\Method\Test();
        $this->assertEquals(
                            $instance->methodArguments(5, 10, 0.5),
                            $proc->exec(5, 10, 0.5)
        );
    }

    public function testCallParameterMap()
    {
        $proc = new MethodProc('Test.methodArguments', 'Prob\\Handler\\Test\\Method');

        $parameterMap = new ParameterMap();
        $parameterMap->bindByName('num3', 0.5);
        $parameterMap->bindByName('num2', 10);
        $parameterMap->bindByName('num1', 5);

        $instance = new Test\Method\Test();

        $this->assertEquals(
                            $instance->methodArguments(5, 10, 0.5),
                            $proc->execWithParameterMap($parameterMap)
        );
    }
}
