<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoBindParameterException;
use Prob\Handler\ParameterInterface;

class ParameterMap
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Bind a parameter
     *
     * @param ParameterInterface $parameter
     * @param mixed $value
     */
    public function bindBy(ParameterInterface $parameter, $value)
    {
        $this->parameters[$parameter->getHash()] = $value;
    }

    /**
     * Get a parameter
     *
     * @param ParameterInterface $parameter
     * @return mixed
     */
    public function getValueBy(ParameterInterface $parameter)
    {
        if ($this->isExistBy($parameter) === false) {
            throw new NoBindParameterException('No parameter: ' . (string) $parameter);
        }

        return $this->parameters[$parameter->getHash()];
    }

    public function isExistBy(ParameterInterface $parameter)
    {
        return isset($this->parameters[$parameter->getHash()]);
    }
}
