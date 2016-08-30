<?php

namespace Prob\Handler\Proc;

use Prob\Handler\ProcInterface;
use Prob\Handler\ParameterMapper;
use Prob\Handler\ParameterMap;
use \ReflectionFunction;

class ClosureProc implements ProcInterface
{
    /**
     * @var Closure
     */
    private $closure = null;

    public function __construct($closure, $namespace = '')
    {
        $this->closure = $closure;
    }

    public function getNamespace()
    {
        return null;
    }
    public function getName()
    {
        return '{closure}';
    }

    public function exec(...$args)
    {
        return ($this->closure)(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $reflection = new ReflectionFunction($this->closure);

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $parameters = $mapper->getMappedParameters($map);

        return $this->exec(...$parameters);
    }
}
