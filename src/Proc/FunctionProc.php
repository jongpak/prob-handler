<?php

namespace Prob\Handler\Proc;

use Prob\Handler\ProcInterface;
use Prob\Handler\ParameterMapper;
use Prob\Handler\ParameterMap;
use \ReflectionFunction;

class FunctionProc implements ProcInterface
{

    private $functionName = '';
    private $namespace = '';

    public function __construct($functionName, $namespace = '')
    {
        $this->functionName = $functionName;
        $this->namespace = $namespace;
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

        $parameters = $mapper->getMappedParametersWithoutReflection();

        return $this->exec(...$parameters);
    }
}
