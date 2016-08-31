<?php

namespace Prob\Handler;

use PHPUnit\Framework\TestCase;
use \ReflectionFunction;
use stdClass;
use Prob\Handler\Test\Method\Test;

class ParameterMapperTest extends TestCase
{

    public function testNameMappingExistParameter()
    {
        $reflection = new ReflectionFunction('Prob\\Handler\\Test\\Functions\\functionArgumentsName');

        $map = new ParameterMap();
        $map->bindByName('arg4', null);
        $map->bindByName('arg3', false);
        $map->bindByName('arg2', 'ok');
        $map->bindByName('arg1', 1);

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
        $map->bindByType(stdClass::class, new stdClass());
        $map->bindByType(Test::class, new Test());

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
        $map->bindByType(stdClass::class, new stdClass());
        $map->bindByType('NotExistType', new Test());

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
        $map->bindByNameWithType(stdClass::class, 'arg2', new stdClass());
        $map->bindByNameWithType(Test::class, 'arg1', new Test());

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
        $map->bindByNameWithType(Test::class, 'notExistName', new Test());
        $map->bindByNameWithType(stdClass::class, 'arg2', new stdClass());
        $map->bindByNameWithType(Test::class, 'arg1', new Test());

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

        $map->bindByName('arg2', 'ok');
        $map->bindByNameWithType(stdClass::class, 'arg2', new stdClass());

        $mapper = new ParameterMapper();
        $mapper->setProcParameters($reflection->getParameters());
        $mapper->setParameterMap($map);

        $this->assertEquals([
            null,
            new stdClass()                      // not 'ok' value (because priority)
        ], $mapper->getMappedParameters());
    }
}
