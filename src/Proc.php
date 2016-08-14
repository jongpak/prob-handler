<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Exception\NoFunctionException;
use Prob\Handler\Exception\NoBindParameterException;

class Proc
{
    /**
     * @var array
     */
    protected $func = null;

    /**
     * Proc constructor.
     * @param string|clojure $func example) [clojure] function() { ... } or [string] 'someFuncName' or 'someClassName.methodName' format
     * @param string $namespace
     * @throws NoClassException
     * @throws NoFunctionException
     * @throws NoMethodException
     */
    public function __construct($func, $namespace = '\\')
    {
        // Closure
        if (is_callable($func)) {
            $this->func = [
                'class' => null,
                'func' => $func
            ];

            return;
        }

        $proc = explode('.', $func);

        // Function
        if (count($proc) < 2) {
            if (function_exists($namespace . '\\' . $func) == false) {
                throw new NoFunctionException('No Function: ' . $namespace . '\\' . $func);
            }

            $this->func = [
                'class' => null,
                'func' => $namespace . '\\' . $func
            ];

            return;
        }

        if (class_exists($namespace . '\\' . $proc[0]) == false) {
            throw new NoClassException('No Class: ' . $namespace . '\\' . $func);
        }

        if (method_exists($namespace . '\\' . $proc[0], $proc[1]) == false) {
            throw new NoMethodException('No Method: ' . $namespace . '\\' . $func);
        }

        // Class method
        $this->func = [
            'class' => $namespace . '\\' . $proc[0],
            'func' => $proc[1]
        ];
    }

    public function exec(...$args)
    {
        if ($this->func['class'] == null) {
            return call_user_func_array($this->func['func'], $args);
        }

        $class = new $this->func['class']();
        return $class->{$this->func['func']}(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $reflection = new ParameterReflection($this->func['class'] ? [$this->func['class'], $this->func['func']] : $this->func['func']);
        $procParameters = $reflection->getParameters();

        $parameters = [];

        foreach ($procParameters as $param) {
            // Name with Type
            try {
                $value = $map->getValueByNameWithType($param['type'], $param['name']);
                $parameters[] = $value;
                continue;
            } catch (NoBindParameterException $e) {
            }

            // only Name
            try {
                $value = $map->getValueByName($param['name']);
                $parameters[] = $value;
                continue;
            } catch (NoBindParameterException $e) {
            }

            // only Type
            try {
                $value = $map->getValueByType($param['type']);
                $parameters[] = $value;
                continue;
            } catch (NoBindParameterException $e) {
            }

            $parameters[] = null;
        }

        return $this->exec(...$parameters);
    }
}
