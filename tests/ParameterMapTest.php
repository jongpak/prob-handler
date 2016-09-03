<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Exception\NoBindParameterException;
use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\Parameter\TypedAndNamed;

class ParameterMapTest extends TestCase
{
    public function testBindByValidName()
    {
        $map = new ParameterMap();
        $map->bindBy(new Named('name'), 'ok');
        $this->assertEquals('ok', $map->getValueBy(new Named('name')));
    }

    public function testbBindByInvalidName()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindBy(new Named('name'), 'ok');
        $map->getValueBy(new Named('noItem'));
    }

    public function testBindByValidType()
    {
        $map = new ParameterMap();
        $map->bindBy(new Typed('TYPE'), 'ok');
        $this->assertEquals('ok', $map->getValueBy(new Typed('TYPE')));
    }

    public function testBindByInvalidType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindBy(new Typed('TYPE'), 'ok');
        $map->getValueBy(new Typed('noItem'));
    }

    public function testBindByValidNameWithType()
    {
        $map = new ParameterMap();
        $map->bindBy(new TypedAndNamed('TYPE', 'name'), 'ok');
        $this->assertEquals('ok', $map->getValueBy(new TypedAndNamed('TYPE', 'name')));
    }

    public function testBindByInvalidNameWithType()
    {
        $this->expectException(NoBindParameterException::class);

        $map = new ParameterMap();
        $map->bindBy(new TypedAndNamed('TYPE', 'name'), 'ok');
        $map->getValueBy(new TypedAndNamed('TYPE', 'noItem'));
    }
}
