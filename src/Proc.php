<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Exception\NoFunctionException;

class Proc
{
    /**
     * @var array
     */
    protected $func = null;

    /**
     * Proc constructor.
     * @param string|clojure $func example) [clojure] function() { ... } or [string] 'someFuncName' or 'someClassName.methodName'
     * @param string $namespace
     * @throws NoClassException
     * @throws NoFunctionException
     * @throws NoMethodException
     */
    public function __construct($func, $namespace = '\\')
    {
        if (is_callable($func)) {
            $this->func = [
                'class' => null,
                'func' => $func
            ];

            return;
        }

        $proc = explode('.', $func);

        if (count($proc) < 2) {
            if (function_exists($namespace . '\\' . $func) == false) {
                throw new NoFunctionException('No Function: ' . $namespace . '\\' . $func);
            }

            $this->func = [
                'class' => null,
                'func' => $func
            ];

            return;
        }

        if (class_exists($namespace . '\\' . $proc[0]) == false)
            throw new NoClassException('No Class: ' . $namespace . '\\' . $func);

        if (method_exists($namespace . '\\' . $proc[0], $proc[1]) == false)
            throw new NoMethodException('No Method: ' . $namespace . '\\' . $func);

        $this->func = [
            'class' => $namespace . '\\' . $proc[0],
            'func' => $proc[1]
        ];
    }

    public function exec(...$args)
    {
        if ($this->func['class'] == null)
            return call_user_func_array($this->func['func'], $args);

        $class = new $this->func['class']();
        return $class->{$this->func['func']}(...$args);
    }
}
