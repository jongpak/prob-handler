<?php

namespace Prob\Handler;

use Prob\Handler\Proc\ClosureProc;
use Prob\Handler\Proc\FunctionProc;
use Prob\Handler\Proc\MethodProc;

class ProcFactory
{

    const TYPE_CLOSURE = 'closure';
    const TYPE_FUNCTION = 'function';
    const TYPE_METHOD = 'method';

    public static function getProc($procedure, $namespace = '')
    {
        switch (static::getProcedureType($procedure)) {
            case static::TYPE_CLOSURE:
                return new ClosureProc($procedure, $namespace);
            case static::TYPE_FUNCTION:
                return new FunctionProc($procedure, $namespace);
            case static::TYPE_METHOD:
                return new MethodProc($procedure, $namespace);
        }
    }

    private static function getProcedureType($procedure)
    {
        if (is_string($procedure) === false && is_callable($procedure)) {
            return static::TYPE_CLOSURE;
        }

        if (count(explode('.', $procedure)) < 2) {
            return static::TYPE_FUNCTION;
        }

        return static::TYPE_METHOD;
    }
}
