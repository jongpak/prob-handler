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
    protected $procedure = null;

    /**
     * Proc constructor.
     * @param string|clojure $procedure [clojure] function() { ... } or [string] 'someFuncName' or 'someClassName.methodName' format
     * @param string $namespace
     * @throws NoClassException
     * @throws NoFunctionException
     * @throws NoMethodException
     */
    public function __construct($procedure, $namespace = '\\')
    {
        // Closure
        if (is_callable($procedure)) {
            $this->setCallableProcedure($procedure);
        } else {
            $proc = explode('.', $procedure);

            // Function
            if (count($proc) < 2) {
                $this->setFunctionProcedure($procedure, $namespace);

            // Class method
            } else {
                $this->setMethodProcedure($procedure, $namespace);
            }
        }
    }

    private function setCallableProcedure($procedure)
    {
        $this->procedure = [
            'class' => null,
            'func' => $procedure
        ];
    }

    private function setFunctionProcedure($procedure, $namespace)
    {
        if (function_exists($namespace . '\\' . $procedure) == false) {
            throw new NoFunctionException('No Function: ' . $namespace . '\\' . $procedure);
        }

        $this->procedure = [
            'class' => null,
            'func' => $namespace . '\\' . $procedure
        ];
    }

    private function setMethodProcedure($procedure, $namespace)
    {
        $proc = explode('.', $procedure);

        if (class_exists($namespace . '\\' . $proc[0]) == false) {
            throw new NoClassException('No Class: ' . $namespace . '\\' . $procedure);
        }

        if (method_exists($namespace . '\\' . $proc[0], $proc[1]) == false) {
            throw new NoMethodException('No Method: ' . $namespace . '\\' . $procedure);
        }

        $this->procedure = [
            'class' => $namespace . '\\' . $proc[0],
            'func' => $proc[1]
        ];
    }

    public function exec(...$args)
    {
        if ($this->procedure['class'] == null) {
            return call_user_func_array($this->procedure['func'], $args);
        }

        $class = new $this->procedure['class']();
        return $class->{$this->procedure['func']}(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $reflection = new ParameterReflection($this->buildProcedureFormat());
        $procParameters = $reflection->getParameters();

        $parameters = [];

        foreach ($procParameters as $param) {
            $type = $param['type'];
            $name = $param['name'];

            // bind Name with Type
            if ($map->isExistBindingParameterByNameWithType($type, $name) === true) {
                $parameters[] = $map->getValueByNameWithType($type, $name);
                continue;

            // bind Name
            } elseif ($map->isExistBindingParameterByName($name) === true) {
                $parameters[] = $map->getValueByName($name);
                continue;

            // bind Type
            } elseif ($map->isExistBindingParameterByType($type) === true) {
                $parameters[] = $map->getValueByType($type);
                continue;

            // no bind
            } else {
                $parameters[] = null;
            }
        }

        return $this->exec(...$parameters);
    }

    private function buildProcedureFormat()
    {
        return $this->procedure['class']
                    ? [ $this->procedure['class'], $this->procedure['func'] ]
                    : $this->procedure['func'];
    }
}
