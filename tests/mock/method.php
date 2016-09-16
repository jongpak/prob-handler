<?php

namespace Prob\Handler\Test\Method;

class Test
{
    private $var;

    public function __construct($var = null)
    {
        $this->var = $var;
    }

    public function methodNoArgument()
    {
        return 'call no argument';
    }

    public function methodArguments($num1, $num2, $num3)
    {
        return $num1 * $num2 * $num3;
    }

    public function getVar()
    {
        return $this->var;
    }
}
