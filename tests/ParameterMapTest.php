<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoBindParameterException;

class ParameterMapTest extends TestCase
{
    public function testBindByValidName()
    {
        $map = new ParameterMap();
        $map->bindByName('test', 'ok');
        $this->assertEquals('ok', $map->getValueByName('test'));
    }

    public function testbBindByInvalidName()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByName('test', 'ok');
        $map->getValueByName('noItem');
    }

    public function testBindByValidType()
    {
        $map = new ParameterMap();
        $map->bindByType(ParameterBindClass::class, 'ok');
        $this->assertEquals('ok', $map->getValueByType(ParameterBindClass::class));
    }

    public function testBindByInvalidType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByType(ParameterBindClass::class, 'ok');
        $map->getValueByType('noItem');
    }

    public function testBindByValidNameWithType()
    {
        $map = new ParameterMap();
        $map->bindByNameWithType(ParameterBindClass::class, 'test', 'ok');
        $this->assertEquals('ok', $map->getValueByNameWithType(ParameterBindClass::class, 'test'));
    }

    public function testBindByInvalidNameWithType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindByNameWithType(ParameterBindClass::class, 'test', 'ok');
        $map->getValueByNameWithType(ParameterBindClass::class, 'noItem');
    }
}

class ParameterBindClass
{

}
