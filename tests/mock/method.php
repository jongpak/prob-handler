<?php

namespace Prob\Handler\Test\Method;

class Test
{

    public function methodNoArgument()
    {
        return 'call no argument';
    }

    public function methodArguments($num1, $num2, $num3)
    {
        return $num1 * $num2 * $num3;
    }
}
