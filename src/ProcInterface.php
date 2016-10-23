<?php

namespace Prob\Handler;

interface ProcInterface
{
    public function __construct($procedure, $namespace = '');

    public function getNamespace();
    public function getName();

    public function exec(...$args);
    public function execWithParameterMap(ParameterMap $map);
}
