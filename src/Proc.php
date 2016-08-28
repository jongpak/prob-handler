<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Exception\NoFunctionException;
use Prob\Handler\Exception\NoBindParameterException;

class Proc
{

    const TYPE_CLOSURE = 'closure';
    const TYPE_FUNCTION = 'function';
    const TYPE_METHOD = 'method';

    /**
     * [clojure] function() { ... }
     * [string] 'someFuncName' or 'someClassName.methodName' format
     *
     * @var string|clojure
     */
    private $procedure = null;
    private $namespace = '';

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
        $this->procedure = $procedure;
        $this->namespace = $namespace;

        $this->validateProcedure();
    }

    /**
     * @throws NoFunctionException
     * @throws NoClassException
     * @throws NoMethodException
     */
    private function validateProcedure()
    {
        $resolvedProcedure = $this->getResolvedProcedureInfo();

        switch ($this->getProcedureType()) {
            case Proc::TYPE_FUNCTION:
                if (function_exists($this->namespace . '\\' . $resolvedProcedure['func']) === false) {
                    throw new NoFunctionException(
                        sprintf('No Function: %s\\%s',
                                    $this->namespace,
                                    $resolvedProcedure['func']
                        )
                    );
                }
                break;

            case Proc::TYPE_METHOD:
                if (class_exists($this->namespace . '\\' . $resolvedProcedure['class']) === false) {
                    throw new NoClassException(
                        sprintf('No Class: %s\\%s',
                                    $this->namespace,
                                    $resolvedProcedure['class']
                        )
                    );
                }

                if (method_exists($this->namespace . '\\' . $resolvedProcedure['class'], $resolvedProcedure['func']) === false) {
                    throw new NoMethodException(
                        sprintf('No Method: %s\\%s::%s',
                                    $this->namespace,
                                    $resolvedProcedure['class'],
                                    $resolvedProcedure['func']
                        )
                    );
                }
                break;
        }
    }

    private function getProcedureType()
    {
        if (is_callable($this->procedure)) {
            return Proc::TYPE_CLOSURE;
        }

        if (count(explode('.', $this->procedure)) < 2) {
            return Proc::TYPE_FUNCTION;
        }

        return Proc::TYPE_METHOD;
    }

    private function getResolvedProcedureInfo()
    {
        $className = null;
        $ClosureOrFunctionName = null;

        switch ($this->getProcedureType()) {
            case Proc::TYPE_CLOSURE:
                $ClosureOrFunctionName = $this->procedure;
                break;

            case Proc::TYPE_FUNCTION:
                $ClosureOrFunctionName = $this->procedure;
                break;

            case Proc::TYPE_METHOD:
                $token = explode('.', $this->procedure);
                $className = $token[0];
                $ClosureOrFunctionName = $token[1];
                break;
        }

        return [
            'namespace' => $this->namespace,
            'class' => $className,
            'func' => $ClosureOrFunctionName
        ];
    }

    public function exec(...$args)
    {
        $resolvedProcedure = $this->getResolvedProcedureInfo();

        switch ($this->getProcedureType()) {
            case Proc::TYPE_CLOSURE:
                return $resolvedProcedure['func'](...$args);
                break;

            case Proc::TYPE_FUNCTION:
                $function = $this->namespace . '\\' . $resolvedProcedure['func'];
                return $function(...$args);
                break;

            case Proc::TYPE_METHOD:
                $className = $this->namespace . '\\' . $resolvedProcedure['class'];
                $instance = new $className();
                return $instance->{$resolvedProcedure['func']}(...$args);
                break;
        }
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $parameters = $this->getResolvedParameterByMap($map);
        return $this->exec(...$parameters);
    }

    private function buildProcedureFormat()
    {
        $resolvedProcedure = $this->getResolvedProcedureInfo();
        return $this->getProcedureType() == Proc::TYPE_METHOD
                    ? [
                        $this->namespace . '\\' . $resolvedProcedure['class'],
                        $resolvedProcedure['func']
                      ]
                    : $this->namespace . $resolvedProcedure['func'];
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
