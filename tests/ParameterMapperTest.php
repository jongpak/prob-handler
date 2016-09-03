<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use Prob\Handler\Test\Method\Test;
use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\Parameter\TypedAndNamed;
use \ReflectionFunction;
use stdClass;

class ParameterMapperTest extends TestCase
{

    public function testNameMappingExistParameter()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsName');

        $map = new ParameterMap();
        $map->bindBy(new Named('arg4'), null);
        $map->bindBy(new Named('arg3'), false);
        $map->bindBy(new Named('arg2'), 'ok');
        $map->bindBy(new Named('arg1'), 1);

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            1,
            'ok',
            false,
            null
        ], $mapper->getMappedParameters());
    }

    public function testNameMappingNotExistParameter()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsName');

        $map = new ParameterMap();

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            null,
            null,
            null,
            null
        ], $mapper->getMappedParameters());
    }

    public function testTypeMappingExist()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsType');

        $map = new ParameterMap();
        $map->bindBy(new Typed(stdClass::class), new stdClass());
        $map->bindBy(new Typed(Test::class), new Test());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            new Test(),
            new stdClass()
        ], $mapper->getMappedParameters());
    }

    public function testTypeMappingNotExist()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsType');

        $map = new ParameterMap();
        $map->bindBy(new Typed(stdClass::class), new stdClass());
        $map->bindBy(new Typed('NotExistType'), new Test());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            null,                                       // NotExistType
            new stdClass()
        ], $mapper->getMappedParameters());
    }


    public function testTypeWithNameMappingExist()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsType');

        $map = new ParameterMap();
        $map->bindBy(new TypedAndNamed(stdClass::class, 'arg2'), new stdClass());
        $map->bindBy(new TypedAndNamed(Test::class, 'arg1'), new Test());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            new Test(),
            new stdClass()
        ], $mapper->getMappedParameters());
    }

    public function testTypeWithNameMappingNotExist()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsType');

        $map = new ParameterMap();
        $map->bindBy(new TypedAndNamed(Test::class, 'notExistName'), new Test());
        $map->bindBy(new TypedAndNamed(stdClass::class, 'arg2'), new stdClass());
        $map->bindBy(new TypedAndNamed(Test::class, 'arg1'), new Test());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            new Test(),
            new stdClass()
        ], $mapper->getMappedParameters());
    }

    public function testNameAndTypeComplex()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsType');

        $map = new ParameterMap();

        $map->bindBy(new Named('arg2'), 'ok');
        $map->bindBy(new TypedAndNamed(stdClass::class, 'arg2'), new stdClass());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            null,
            new stdClass()                      // not 'ok' value (because priority)
        ], $mapper->getMappedParameters());
    }
}
