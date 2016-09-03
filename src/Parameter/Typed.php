<?php

namespace Prob\Handler\Parameter;

use Prob\Handler\ParameterInterface;

class Typed implements ParameterInterface
{

    private $type;

    public function __construct($type)
    {
        $this->setType($type);
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isEqual(ParameterInterface $parameter)
    {
        if ($parameter instanceof Typed === false) {
            return false;
        }

        return $this->type === $parameter->getType();
    }

    public function getHash()
    {
        return md5(Typed::class . '::' . $this->type);
    }

    public function __toString()
    {
        return '{Typed Parameter} type: ' . $this->type;
    }
}
