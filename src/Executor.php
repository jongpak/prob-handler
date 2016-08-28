<?php

namespace Prob\Handler;

class Executor
{

    /**
     * @var Proc
     */
    private $proc;

    private $type;
    private $resolvedName = [];
    private $namespace = '';

    public function __construct(Proc $proc)
    {
        $this->proc = $proc;

        $this->type = $proc->getType();
        $this->resolvedName = $proc->getResolvedName();
        $this->namespace = $this->resolvedName['namespace'];
    }

    public function exec(...$args)
    {
        switch ($this->type) {
            case Proc::TYPE_CLOSURE:
                return $this->resolvedName['func'](...$args);
                break;

            case Proc::TYPE_FUNCTION:
                $function = $this->namespace . '\\' . $this->resolvedName['func'];
                return $function(...$args);
                break;

            case Proc::TYPE_METHOD:
                $className = $this->namespace . '\\' . $this->resolvedName['class'];
                $instance = new $className();
                return $instance->{$this->resolvedName['func']}(...$args);
                break;
        }
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $mapper = new ParameterMapper();
        $mapper->setParameterMap($map);
        $mapper->setProc($this->proc);

        return $this->exec(...$mapper->getMappedParameters());
    }
}
