<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use \InvalidArgumentException;

class ParameterReflectionTest extends TestCase
{
    public function testInvalidFunctionName()
    {
        $this->expectException(InvalidArgumentException::class);

        new ParameterReflection('Prob\\Handler\\xxx_Invalid_Function');
    }

    public function testGetParametersFunctionWithNoArgument()
    {
        $reflection = new ParameterReflection('Prob\\Handler\\testFunctionWithoutArgs');
        $this->assertEquals([], $reflection->getParameters());
    }

    public function testGetParametersFunctionWithArguments()
    {
        $reflection = new ParameterReflection('Prob\\Handler\\testFunctionWithArgs');
        $this->assertEquals([
            [
                'type' => 'array',
                'name' => 'arg1'
            ],
            [
                'type' => ParameterDummy::class,
                'name' => 'arg2'
            ],
            [
                'type' => null,
                'name' => 'arg3'
            ]
        ], $reflection->getParameters());
    }

    public function testGetParametersMethodWithNoArgumentsByClassName()
    {
        $reflection = new ParameterReflection([ReflectionTestClass::class, 'testMethodWithoutArgs']);
        $this->assertEquals([], $reflection->getParameters());
    }

    public function testGetParametersMethodWithArgumentsByClassName()
    {
        $reflection = new ParameterReflection([ReflectionTestClass::class, 'testMethodWithArgs']);
        $this->assertEquals([
            [
                'type' => 'array',
                'name' => 'arg1'
            ],
            [
                'type' => ParameterDummy::class,
                'name' => 'arg2'
            ],
            [
                'type' => null,
                'name' => 'arg3'
            ]
        ], $reflection->getParameters());
    }

    public function testGetParametersMethodWithNoArgumentsByClassIntance()
    {
        $reflection = new ParameterReflection([new ReflectionTestClass(), 'testMethodWithoutArgs']);
        $this->assertEquals([], $reflection->getParameters());
    }

    public function testGetParametersMethodWithArgumentsByClassIntance()
    {
        $reflection = new ParameterReflection([new ReflectionTestClass(), 'testMethodWithArgs']);
        $this->assertEquals([
            [
                'type' => 'array',
                'name' => 'arg1'
            ],
            [
                'type' => ParameterDummy::class,
                'name' => 'arg2'
            ],
            [
                'type' => null,
                'name' => 'arg3'
            ]
        ], $reflection->getParameters());
    }

    public function testGetParametersClojureWithNoArguments()
    {
        $reflection = new ParameterReflection(function () {});
        $this->assertEquals([], $reflection->getParameters());
    }

    public function testGetParametersClojureWithArguments()
    {
        $reflection = new ParameterReflection(function (array $arg1, ParameterDummy $arg2, $arg3) {});
        $this->assertEquals([
            [
                'type' => 'array',
                'name' => 'arg1'
            ],
            [
                'type' => ParameterDummy::class,
                'name' => 'arg2'
            ],
            [
                'type' => null,
                'name' => 'arg3'
            ]
        ], $reflection->getParameters());
    }
}

class ParameterDummy
{
}

class ReflectionTestClass
{
    public function testMethodWithoutArgs(/* no argument */)
    {
    }

    public function testMethodWithArgs(array $arg1, ParameterDummy $arg2, $arg3)
    {
    }
}

function testFunctionWithoutArgs(/* no argument */)
{
}

function testFunctionWithArgs(array $arg1, ParameterDummy $arg2, $arg3)
{
}
