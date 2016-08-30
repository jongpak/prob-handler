<?php

namespace Prob\Handler;

class ParameterMapper
{

    /**
     * @var ParameterMap
     */
    private $map;

    private $parameters = [];

    public function setParameterMap(ParameterMap $map)
    {
        $this->map = $map;
    }

    public function setProcParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * @return array
     */
    public function getMappedParameters()
    {
        $mappedParameters = [];

        /**
         * @var ReflectionParameter $param
         */
        foreach ($this->parameters as $param) {
            $mappedParameters[] = $this->getMatchedParameter($param->getType(), $param->getName());
        }

        return $mappedParameters;
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
