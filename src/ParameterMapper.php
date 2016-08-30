<?php

namespace Prob\Handler;

class ParameterMapper
{

    /**
     * @var ParameterMap
     */
    private $map;

    private $procParameters = [];

    public function setParameterMap(ParameterMap $map)
    {
        $this->map = $map;
    }

    public function setProcParameters(array $procParameters)
    {
        $this->procParameters = $procParameters;
    }


    /**
     * @return array
     */
    public function getMappedParameters()
    {
        $parameters = [];

        /**
         * @var ReflectionParameter $param
         */
        foreach ($this->procParameters as $param) {
            $parameters[] = $this->getMatchedParameter($param->getType(), $param->getName());
        }

        return $parameters;
    }

    private function getMatchedParameter($type, $name)
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
}