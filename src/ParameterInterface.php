<?php

namespace Prob\Handler;

interface ParameterInterface
{
    public function isEqual(ParameterInterface $parameter);
    public function getHash();

    public function __toString();
}
