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

    /**
     * Bind a parameter by name
     *
     * @param string $name name of parameter
     * @param mixed $value value
     */
    public function bindByName($name, $value)
    {
        $this->names[$name] = $value;
    }

    /**
     * Bind a parameter by type
     *
     * @param string $type type of parameter
     * @param mixed $value value
     */
    public function bindByType($type, $value)
    {
        $this->types[$type] = $value;
    }

    /**
     * Bind a parameter by name and type
     *
     * @param string $type type of parameter
     * @param string $name name of parameter
     * @param mixed $value value
     */
    public function bindByNameWithType($type, $name, $value)
    {
        $this->nameWithTypes[$type][$name] = $value;
    }

    /**
     * Get a value with matching name
     *
     * @param string $name name of parameter
     */
    public function getValueByName($name)
    {
        if(isset($this->names[$name]) === false)
            throw new NoBindParameterException('No parameter name: ' . $name);

        return $this->names[$name];
    }

    /**
     * Get a value with matching type
     *
     * @param string $type type of parameter
     */
    public function getValueByType($type)
    {
        if(isset($this->types[$type]) === false)
            throw new NoBindParameterException('No parameter type: ' . $type);

        return $this->types[$type];
    }

    /**
     * Get a value with matching name and type
     *
     * @param string $type type of parameter
     * @param string $name name of parameter
     */
    public function getValueByNameWithType($type, $name)
    {
        if(isset($this->nameWithTypes[$type]) === false || isset($this->nameWithTypes[$type][$name]) === false)
            throw new NoBindParameterException('No parameter type and name: ' . $type . ', ' . $name);

        return $this->nameWithTypes[$type][$name];
    }
}
