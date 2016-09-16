<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc\MethodProc;
use Prob\Handler\ParameterMap;
use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Parameter\Named;

class MethodProcTest extends TestCase
{
    public function setUp()
    {
        require_once 'mock/method.php';
        require_once 'mock/noConstructorClass.php';
    }

    public function testNoClassException()
    {
        $this->expectException(NoClassException::class);
        $proc = new MethodProc('???.???');
    }

    public function testNoMethodException()
    {
        $this->expectException(NoMethodException::class);
        $proc = new MethodProc('Test.???', 'Prob\\Handler\\Test\\Method');
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
        $parameterMap->bindBy(new Named('num3'), 0.5);
        $parameterMap->bindBy(new Named('num2'), 10);
        $parameterMap->bindBy(new Named('num1'), 5);

        $instance = new Test\Method\Test();

        $this->assertEquals(
                            $instance->methodArguments(5, 10, 0.5),
                            $proc->execWithParameterMap($parameterMap)
        );
    }

    public function testExecConstructor1()
    {
        $proc = new MethodProc('Test.getVar', 'Prob\\Handler\\Test\\Method');
        $this->assertEquals(null, $proc->exec());
    }

    public function testExecConstructor2()
    {
        $proc = new MethodProc('Test.getVar', 'Prob\\Handler\\Test\\Method');
        $proc->execConstructor('test');
        $this->assertEquals('test', $proc->exec());
    }

    public function testExecConstructorWithParameterMap()
    {
        $map = new ParameterMap();
        $map->bindBy(new Named('var'), 'test');

        $proc = new MethodProc('Test.getVar', 'Prob\\Handler\\Test\\Method');
        $proc->execConstructorWithParameterMap($map);
        $this->assertEquals('test', $proc->exec());
    }

    public function testNoConstructor()
    {
        $map = new ParameterMap();
        $map->bindBy(new Named('var'), 'test');

        $proc = new MethodProc('noConstructorClass.testMethod', 'Prob\\Handler\\Test\\Method');
        $proc->execConstructorWithParameterMap($map);
        $this->assertEquals('test', $proc->exec());
    }
}
