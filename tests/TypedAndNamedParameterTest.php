<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Parameter\TypedAndNamed;

class TypedAndNamedParameterTest extends TestCase
{
    public function testNameTest1()
    {
        $parameter = new TypedAndNamed('Test', 'test');
        $this->assertEquals('Test', $parameter->getType());
        $this->assertEquals('test', $parameter->getName());
    }
}
