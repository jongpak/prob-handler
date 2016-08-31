<?php

namespace Prob\Handler\Test\Functions;

use Prob\Handler\Test\Method\Test;
use \stdClass;

function functionNoArgument()
{
    return 'call no argument';
}

function functionArguments($num1, $num2, $num3)
{
    return $num1 * $num2 * $num3;
}

function functionArgumentsName($arg1, $arg2, $arg3, $arg4)
{
}

function functionArgumentsType(Test $arg1, stdClass $arg2)
{
}
