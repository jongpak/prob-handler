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
        $this->setProcedure($procedure, $namespace);
    }

    private function setProcedure($procedure, $namespace)
    {
        $this->validateProcedure($procedure, $namespace);
        $this->procedure = $this->getResolvedProcedureInfo($procedure, $namespace);
    }

    /**
     * @throws NoFunctionException
     * @throws NoClassException
     * @throws NoMethodException
     */
    private function validateProcedure($procedure, $namespace)
    {
        switch ($this->getProcedureType($procedure)) {
            case 'function':
                if (function_exists($resolvedProcedure['func']) === false) {
                    throw new NoFunctionException('No Function: ' . $namespace . '\\' . $procedure);
                }
                break;

            case 'method':
                if (class_exists($resolvedProcedure['class']) === false) {
                    throw new NoClassException('No Class: ' . $namespace . '\\' . $procedure);
                }

                if (method_exists($resolvedProcedure['class'], $resolvedProcedure['func']) === false) {
                    throw new NoMethodException('No Method: ' . $namespace . '\\' . $procedure);
                }
                break;
        }
    }

    private function getProcedureType($procedure)
    {
        if (is_callable($procedure)) {
            return 'closure';
        }

        if (count(explode('.', $procedure)) < 2) {
            return 'function';
        }

        return 'method';
    }

    private function getResolvedProcedureInfo($procedure, $namespace)
    {
        $className = null;
        $ClosureOrFunctionName = null;

        switch ($this->getProcedureType($procedure)) {
            case 'closure':
                $ClosureOrFunctionName = $procedure;
                break;

            case 'function':
                $ClosureOrFunctionName = $namespace . '\\' . $procedure;
                break;

            case 'method':
                $token = explode('.', $procedure);
                $className = $namespace . '\\' . $token[0];
                $ClosureOrFunctionName = $token[1];
                break;
        }

        return [
            'class' => $className,
            'func' => $ClosureOrFunctionName
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
        $parameters = $this->getResolvedParameterByMap($map);
        return $this->exec(...$parameters);
    }

    private function buildProcedureFormat()
    {
        return $this->procedure['class']
                    ? [ $this->procedure['class'], $this->procedure['func'] ]
                    : $this->procedure['func'];
    }

    private function getResolvedParameterByMap(ParameterMap $map)
    {
        $reflection = new ParameterReflection($this->buildProcedureFormat());
        $procParameters = $reflection->getParameters();

        $parameters = [];

        foreach ($procParameters as $param) {
            $parameters[] = $this->getMatchedParameterByMap($map, $param);
        }

        return $parameters;
    }

    private function getMatchedParameterByMap(ParameterMap $map, array $parameter)
    {
        $type = $parameter['type'];
        $name = $parameter['name'];

        // bind Name with Type
        if ($map->isExistBindingParameterByNameWithType($type, $name) === true) {
            return $map->getValueByNameWithType($type, $name);
        }

        // bind Name
        if ($map->isExistBindingParameterByName($name) === true) {
            return $map->getValueByName($name);
        }

        // bind Type
        if ($map->isExistBindingParameterByType($type) === true) {
            return $map->getValueByType($type);
        }
    }
}
