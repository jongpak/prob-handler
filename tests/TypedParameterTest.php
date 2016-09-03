<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Parameter\Typed;

class TypedParameterTest extends TestCase
{

    public function testNameTest1()
    {
        $parameter = new Typed('Test');
        $this->assertEquals('Test', $parameter->getType());
    }
}
