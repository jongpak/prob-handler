<?php

namespace Prob\Handler;

use Prob\Handler\Exception\NoClassException;
use Prob\Handler\Exception\NoMethodException;
use Prob\Handler\Exception\NoFunctionException;
use Prob\Handler\Exception\NoBindParameterException;

class Proc
{

    const TYPE_CLOSURE = 'closure';
    const TYPE_FUNCTION = 'function';
    const TYPE_METHOD = 'method';

    /**
     * [clojure] function() { ... }
     * [string] 'someFuncName' or 'someClassName.methodName' format
     *
     * @var string|clojure
     */
    private $procedure = null;
    private $namespace = '';

    /**
     * Proc constructor.
     * @param string|clojure $procedure [clojure] function() { ... } or [string] 'someFuncName' or 'someClassName.methodName' format
     * @param string $namespace
     * @throws NoClassException
     * @throws NoFunctionException
     * @throws NoMethodException
     */
    public function __construct($procedure, $namespace = '\\')
    {
        $this->procedure = $procedure;
        $this->namespace = $namespace;

        $this->validateProcedure();
    }

    /**
     * @throws NoFunctionException
     * @throws NoClassException
     * @throws NoMethodException
     */
    private function validateProcedure()
    {
        $resolvedProcedure = $this->getResolvedName();

        switch ($this->getType()) {
            case Proc::TYPE_FUNCTION:
                if (function_exists($this->namespace . '\\' . $resolvedProcedure['func']) === false) {
                    throw new NoFunctionException(
                        sprintf('No Function: %s\\%s',
                                    $this->namespace,
                                    $resolvedProcedure['func']
                        )
                    );
                }
                break;

            case Proc::TYPE_METHOD:
                if (class_exists($this->namespace . '\\' . $resolvedProcedure['class']) === false) {
                    throw new NoClassException(
                        sprintf('No Class: %s\\%s',
                                    $this->namespace,
                                    $resolvedProcedure['class']
                        )
                    );
                }

                if (method_exists($this->namespace . '\\' . $resolvedProcedure['class'], $resolvedProcedure['func']) === false) {
                    throw new NoMethodException(
                        sprintf('No Method: %s\\%s::%s',
                                    $this->namespace,
                                    $resolvedProcedure['class'],
                                    $resolvedProcedure['func']
                        )
                    );
                }
                break;
        }
    }

    public function getType()
    {
        if (is_callable($this->procedure)) {
            return Proc::TYPE_CLOSURE;
        }

        if (count(explode('.', $this->procedure)) < 2) {
            return Proc::TYPE_FUNCTION;
        }

        return Proc::TYPE_METHOD;
    }

    public function getResolvedName()
    {
        $className = null;
        $ClosureOrFunctionName = null;

        switch ($this->getType()) {
            case Proc::TYPE_CLOSURE:
                $ClosureOrFunctionName = $this->procedure;
                break;

            case Proc::TYPE_FUNCTION:
                $ClosureOrFunctionName = $this->procedure;
                break;

            case Proc::TYPE_METHOD:
                $token = explode('.', $this->procedure);
                $className = $token[0];
                $ClosureOrFunctionName = $token[1];
                break;
        }

        return [
            'namespace' => $this->namespace,
            'class' => $className,
            'func' => $ClosureOrFunctionName
        ];
    }

    public function exec(...$args)
    {
        $executor = new Executor($this);
        return $executor->exec(...$args);
    }

    public function execWithParameterMap(ParameterMap $map)
    {
        $executor = new Executor($this);
        return $executor->execWithParameterMap($map);
    }
}
