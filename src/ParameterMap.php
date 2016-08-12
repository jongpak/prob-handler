<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoBindParameterException;

class ParameterMap
{
    /**
     * @var array
     */
    private $names = [];

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $nameWithTypes = [];

    public function bindByName($name, $value)
    {
        $this->names[$name] = $value;
    }

    public function bindByType($type, $value)
    {
        $this->types[$type] = $value;
    }

    public function bindByNameWithType($type, $name, $value)
    {
        $this->nameWithTypes[$type][$name] = $value;
    }

    public function getValueByName($name)
    {
        if(isset($this->names[$name]) === false)
            throw new NoBindParameterException('No parameter name: ' . $name);

        return $this->names[$name];
    }

    public function getValueByType($type)
    {
        if(isset($this->types[$type]) === false)
            throw new NoBindParameterException('No parameter type: ' . $type);

        return $this->types[$type];
    }

    public function getValueByNameWithType($type, $name)
    {
        if(isset($this->nameWithTypes[$type]) === false || isset($this->nameWithTypes[$type][$name]) === false)
            throw new NoBindParameterException('No parameter type and name: ' . $type . ', ' . $name);

        return $this->nameWithTypes[$type][$name];
    }
}
