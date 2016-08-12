<?php

namespace Prob\Handler;

use \Closure;
use \InvalidArgumentException;
use \ReflectionFunction;
use \ReflectionMethod;

class ParameterReflection
{
    /**
     * closure | method | function
     *
     * @var string
     */
    private $procedureType = null;

    /**
     * @var ReflectionParameter
     */
    private $reflection = null;

    /**
     *
     * @var closure|string|array $procedureName (string)name of funcion or (array) method of class or (closure) closure
     */
    public function __construct($procedureName)
    {
        $procedureType = $this->resolveFunctionType($procedureName);

        if($procedureType === null)
            throw new InvalidArgumentException('Invalid function or method name: ' . var_export($procedureName, true));

        $reflection = $this->getReflectionProcedure($procedureType, $procedureName);

        $this->procedureType = $procedureType;
        $this->reflection = $reflection;
    }

    private function resolveFunctionType($procedureName)
    {
        // Closure
        if($procedureName instanceof Closure) {
            return 'closure';

        // Class method
        } elseif(gettype($procedureName) === 'array') {
            if(is_callable($procedureName) === true) {
                return 'method';
            }

        // Function
        } elseif(gettype($procedureName) === 'string') {
            if(is_callable($procedureName) === true) {
                return 'function';
            }
        }

        return null;
    }

    private function getReflectionProcedure($procedureType, $procedureName)
    {
        if($procedureType === 'closure' || $procedureType === 'function')
            return new ReflectionFunction($procedureName);
        elseif($procedureType === 'method')
            return new ReflectionMethod($procedureName[0], $procedureName[1]);
    }

    public function getParameters()
    {
        $parameters = $this->reflection->getParameters();
        $resolvedParameters = [];

        if(count($parameters) === 0)
            return [];

        foreach($parameters as $param) {
            $resolvedParameters[] = [
                'type' => (string)$param->getType(),
                'name' => $param->getName()
            ];
        }

        return $resolvedParameters;
    }
}
