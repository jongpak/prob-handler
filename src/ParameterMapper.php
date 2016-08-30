<?php

namespace Prob\Handler;

class ParameterMapper
{

    /**
     * @var ParameterMap
     */
    private $map;

    /**
     * @var array
     */
    private $resolvedProcName;
    private $procType;

    private $parameters = [];

    public function setParameterMap(ParameterMap $map)
    {
        $this->map = $map;
    }

    public function setProc(Proc $proc)
    {
        $this->resolvedProcName = $proc->getResolvedName();
        $this->procType = $proc->getType();
    }

    public function setProcParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }


    public function getMappedParametersWithoutReflection()
    {
        $mapedParameters = [];

        foreach ($this->parameters as $param) {
            $mapedParameters[] = $this->getMatchedParameterByMapWithPrototype($param->getType(), $param->getName());
        }

        return $mapedParameters;
    }

    /**
     * @return array
     */
    public function getMappedParameters()
    {
        $reflection = new ParameterReflection($this->buildProcedureFormat());
        $parameterPrototypes = $reflection->getParameters();

        $parameters = [];

        foreach ($parameterPrototypes as $param) {
            $parameters[] = $this->getMatchedParameterByMap($param);
        }

        return $parameters;
    }

    private function buildProcedureFormat()
    {
        if ($this->procType === Proc::TYPE_METHOD) {
            return [
                    $this->resolvedProcName['namespace'] . '\\' . $this->resolvedProcName['class'],
                    $this->resolvedProcName['func']
            ];
        }

        return $this->resolvedProcName['namespace'] . $this->resolvedProcName['func'];
    }

    private function getMatchedParameterByMapWithPrototype($type, $name)
    {
        // bind Name with Type
        if ($this->map->isExistBindingParameterByNameWithType($type, $name) === true) {
            return $this->map->getValueByNameWithType($type, $name);
        }

        // bind Name
        if ($this->map->isExistBindingParameterByName($name) === true) {
            return $this->map->getValueByName($name);
        }

        // bind Type
        if ($this->map->isExistBindingParameterByType($type) === true) {
            return $this->map->getValueByType($type);
        }
    }

    private function getMatchedParameterByMap(array $parameter)
    {
        $type = $parameter['type'];
        $name = $parameter['name'];

        // bind Name with Type
        if ($this->map->isExistBindingParameterByNameWithType($type, $name) === true) {
            return $this->map->getValueByNameWithType($type, $name);
        }

        // bind Name
        if ($this->map->isExistBindingParameterByName($name) === true) {
            return $this->map->getValueByName($name);
        }

        // bind Type
        if ($this->map->isExistBindingParameterByType($type) === true) {
            return $this->map->getValueByType($type);
        }
    }
}
