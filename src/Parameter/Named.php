<?php

namespace Prob\Handler\Parameter;

use Prob\Handler\ParameterInterface;

class Named implements ParameterInterface
{
    private $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isEqual(ParameterInterface $parameter)
    {
        if ($parameter instanceof Named === false) {
            return false;
        }

        return $this->name === $parameter->getName();
    }

    public function getHash()
    {
        return md5(Named::class . '::' . $this->name);
    }

    public function __toString()
    {
        return '{Named Parameter} name: ' . $this->name;
    }
}
