<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Parameter\Named;

class NamedParameterTest extends TestCase
{

    public function testNameTest1()
    {
        $parameter = new Named('test');
        $this->assertEquals('test', $parameter->getName());
    }
}
