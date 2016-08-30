<?php

namespace Prob\Handler\Test\Functions;

function functionNoArgument()
{
    return 'call no argument';
}

function functionArguments($num1, $num2, $num3)
{
    return $num1 * $num2 * $num3;
}
