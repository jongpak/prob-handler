<?php

namespace Prob\Handler;

use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\Parameter\TypedAndNamed;

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

    /**
     * @param array $procParameters return of ReflectionFunctionAbstract::getParameters(), array of ReflectionParameter
     */
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
            $parameters[] = $this->getMatchedParameter((string) $param->getType(), $param->getName());
        }

        return $parameters;
    }

    private function getMatchedParameter($type, $name)
    {
        $matchingParameters = [
            new TypedAndNamed($type, $name),
            new Named($name),
            new Typed($type)
        ];

        foreach ($matchingParameters as $parameter) {
            if ($this->map->isExistBy($parameter)) {
                return $this->map->getValueBy($parameter);
            }
        }
    }
}
