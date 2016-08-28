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
        $parameters = $this->getResolvedParameterByMap($map);
        return $this->exec(...$parameters);
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

    private function buildProcedureFormat()
    {
        return $this->type == Proc::TYPE_METHOD
                    ? [
                        $this->namespace . '\\' . $this->resolvedName['class'],
                        $this->resolvedName['func']
                      ]
                    : $this->namespace . $this->resolvedName['func'];
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
