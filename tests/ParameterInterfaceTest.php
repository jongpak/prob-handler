<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\Parameter\TypedAndNamed;

class ParameterInterfaceTest extends TestCase
{

    public function testIsEqual1()
    {
        $parameter = new Named('test');

        $this->assertEquals(true, $parameter->isEqual(new Named('test')));
        $this->assertEquals(false, $parameter->isEqual(new Named('not_test')));

        $this->assertEquals(false, $parameter->isEqual(new Typed('test')));
        $this->assertEquals(false, $parameter->isEqual(new TypedAndNamed('Test', 'test')));
    }

    public function testIsEqual2()
    {
        $parameter = new Typed('Test');

        $this->assertEquals(true, $parameter->isEqual(new Typed('Test')));
        $this->assertEquals(false, $parameter->isEqual(new Typed('NotTest')));

        $this->assertEquals(false, $parameter->isEqual(new Named('Test')));
        $this->assertEquals(false, $parameter->isEqual(new TypedAndNamed('Test', 'test')));
    }

    public function testIsEqual3()
    {
        $parameter = new TypedAndNamed('Test', 'test');

        $this->assertEquals(true, $parameter->isEqual(new TypedAndNamed('Test', 'test')));
        $this->assertEquals(false, $parameter->isEqual(new TypedAndNamed('Test', 'notTest')));
        $this->assertEquals(false, $parameter->isEqual(new TypedAndNamed('NotTest', 'test')));

        $this->assertEquals(false, $parameter->isEqual(new Named('test')));
        $this->assertEquals(false, $parameter->isEqual(new Typed('Test')));
    }
}
