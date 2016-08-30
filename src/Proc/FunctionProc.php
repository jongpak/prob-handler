<?php

namespace Prob\Handler\Proc;

use Prob\Handler\ProcInterface;
use Prob\Handler\ParameterMapper;
use Prob\Handler\ParameterMap;
use Prob\Handler\Exception\NoFunctionException;
use \ReflectionFunction;

class FunctionProc implements ProcInterface
{

    private $functionName = '';
    private $namespace = '';

    public function __construct($functionName, $namespace = '')
    {
        $this->functionName = $functionName;
        $this->namespace = $namespace;

        $this->validate();
    }

    private function validate()
    {
        if (function_exists($this->namespace . '\\' . $this->functionName)) {
            return;
        }

        throw new NoFunctionException(
            sprintf('No Function: %s\\%s', $this->namespace, $this->functionName)
        );
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
    public function getName()
    {
        return $this->functionName;
    }

    public function exec(...$args)
    {
        $functionFullName = $this->namespace . '\\' . $this->functionName;
        return ($functionFullName)(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $reflection = new ReflectionFunction($this->namespace . '\\' . $this->functionName);

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $parameters = $mapper->getMappedParameters();

        return $this->exec(...$parameters);
    }
}
