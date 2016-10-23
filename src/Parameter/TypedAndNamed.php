<?php

namespace Prob\Handler\Parameter;

use Prob\Handler\ParameterInterface;

class TypedAndNamed implements ParameterInterface
{
    private $type;
    private $name;

    public function __construct($type, $name)
    {
        $this->setType($type);
        $this->setName($name);
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
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
        if ($parameter instanceof TypedAndNamed === false) {
            return false;
        }

        return $this->type === $parameter->getType()
                && $this->name === $parameter->getName();
    }

    public function getHash()
    {
        return md5(TypedAndNamed::class . '::' . $this->type . '::' . $this->name);
    }

    public function __toString()
    {
        return '{Typed and Named Parameter} name: ' . $this->name . ', type: ' . $this->type;
    }
}
