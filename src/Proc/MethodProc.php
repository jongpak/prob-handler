<?php

namespace Prob\Handler\Proc;

use Prob\Handler\ProcInterface;
use Prob\Handler\ParameterMapper;
use Prob\Handler\ParameterMap;
use \ReflectionMethod;

class MethodProc implements ProcInterface
{

    private $className = '';
    private $methodName = '';

    private $namespace = '';

    public function __construct($methodNameWithClassName, $namespace = '')
    {
        $token = explode('.', $methodNameWithClassName);
        $this->className = $token[0];
        $this->methodName = $token[1];

        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
    public function getName()
    {
        return sprintf('%s.%s', $this->className, $this->methodName);
    }

    public function exec(...$args)
    {
        $functionFullName = $this->namespace . '\\' . $this->className;

        $instance = new $functionFullName();
        return $instance->{$this->methodName}(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $reflection = new ReflectionMethod($this->namespace . '\\' . $this->className, $this->methodName);

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $parameters = $mapper->getMappedParameters();

        return $this->exec(...$parameters);
    }
}
