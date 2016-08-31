<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Proc\MethodProc;
use Prob\Handler\Proc\ClosureProc;
use Prob\Handler\Proc\FunctionProc;

class ProcFactoryTest extends TestCase
{

    public function testClosureFactory1()
    {
        $proc = ProcFactory::getProc(function () {});
        $this->assertEquals(ClosureProc::class, get_class($proc));
    }

    public function testClosureFactory2()
    {
        $closure = function () {};
        $proc = ProcFactory::getProc($closure);
        $this->assertEquals(ClosureProc::class, get_class($proc));
    }


    public function testFunctionFactory()
    {
        $proc = ProcFactory::getProc('pi');
        $this->assertEquals(FunctionProc::class, get_class($proc));
    }


    public function testMethodFactory()
    {
        require_once 'mock/method.php';

        $proc = ProcFactory::getProc('Test.methodNoArgument', 'Prob\\Handler\\Test\\Method');
        $this->assertEquals(MethodProc::class, get_class($proc));
    }
}
