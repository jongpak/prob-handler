<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoBindParameterException;

class ParameterMapTest extends TestCase
{
    public function testBindByValidName()
    {
        $map = new ParameterMap();
        $map->bindByName('name', 'ok');
        $this->assertEquals('ok', $map->getValueByName('name'));
    }

    public function testbBindByInvalidName()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByName('name', 'ok');
        $map->getValueByName('noItem');
    }

    public function testBindByValidType()
    {
        $map = new ParameterMap();
        $map->bindByType('TYPE', 'ok');
        $this->assertEquals('ok', $map->getValueByType('TYPE'));
    }

    public function testBindByInvalidType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByType('TYPE', 'ok');
        $map->getValueByType('noItem');
    }

    public function testBindByValidNameWithType()
    {
        $map = new ParameterMap();
        $map->bindByNameWithType('TYPE', 'name', 'ok');
        $this->assertEquals('ok', $map->getValueByNameWithType('TYPE', 'name'));
    }

    public function testBindByInvalidNameWithType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByNameWithType('TYPE', 'name', 'ok');
        $map->getValueByNameWithType('TYPE', 'noItem');
    }
}
