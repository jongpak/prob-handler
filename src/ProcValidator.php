<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Exception\NoFunctionException;
use Prob\Handler\Exception\NoBindParameterException;

class ProcValidator
{

    /**
     * @throws NoFunctionException
     * @throws NoClassException
     * @throws NoMethodException
     */
    public static function validate(Proc $proc)
    {
        $namespace = $proc->getResolvedName()['namespace'];
        $className = $proc->getResolvedName()['class'];
        $func = $proc->getResolvedName()['func'];

        switch ($proc->getType()) {
            case Proc::TYPE_FUNCTION:
                if (function_exists($namespace . '\\' . $func) === false) {
                    throw new NoFunctionException(self::getMessageNoFunction($namespace, $func));
                }
                break;

            case Proc::TYPE_METHOD:
                if (class_exists($namespace . '\\' . $className) === false) {
                    throw new NoClassException(self::getMessageNoClass($namespace, $className));
                }

                if (method_exists($namespace . '\\' . $className, $func) === false) {
                    throw new NoMethodException(self::getMessageNoMethod($namespace, $className, $func));
                }
                break;
        }
    }

    private static function getMessageNoFunction($namespace, $functionName)
    {
        return sprintf('No Function: %s\\%s', $namespace, $functionName);
    }

    private static function getMessageNoClass($namespace, $className)
    {
        return sprintf('No Class: %s\\%s', $namespace, $className);
    }

    private static function getMessageNoMethod($namespace, $className, $methodName)
    {
        return sprintf('No Method: %s\\%s::%s', $namespace, $className, $methodName);
    }
}
