<?php

namespace Prob\Handler;

use \Closure;
use \InvalidArgumentException;
use \ReflectionFunction;
use \ReflectionMethod;

class ParameterReflection
{
    /**
     * (string) procedure type: closure | method | function
     *
     * @var string
     */
    private $procedureType = null;

    /**
     * @var ReflectionParameter[] a array of ReflectionParameter class
     */
    private $parameters = [];

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
        $this->parameters = $reflection->getParameters();
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

    /**
     * @return ReflectionFunctionAbstract
     */
    private function getReflectionProcedure($procedureType, $procedureName)
    {
        if($procedureType === 'closure' || $procedureType === 'function')
            return new ReflectionFunction($procedureName);
        elseif($procedureType === 'method')
            return new ReflectionMethod($procedureName[0], $procedureName[1]);
    }

    /**
     * Get parameters of a function or method
     *
     * return value:
     * array[index]
     *          ['type']    string A type of parameter
     *          ['name']    string A name of parameter
     *
     * @return array
     */
    public function getParameters()
    {
        $resolvedParameters = [];

        if(count($this->parameters) === 0)
            return [];

        foreach($this->parameters as $param) {
            $resolvedParameters[] = [
                'type' => (string)$param->getType(),
                'name' => $param->getName()
            ];
        }

        return $resolvedParameters;
    }
}
