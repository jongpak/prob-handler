# Prob/Handler
*A simple library for calling function and method*

[![Build Status](https://travis-ci.org/jongpak/prob-handler.svg?branch=master)](https://travis-ci.org/jongpak/prob-handler)
[![codecov](https://codecov.io/gh/jongpak/prob-handler/branch/master/graph/badge.svg)](https://codecov.io/gh/jongpak/prob-handler)

## Usage

### calling function
#### function in global namespace
```php
<?php

use Prob\Handler\ProcFactory;

$procA = ProcFactory::getProc('pi');
echo $pro;                  // 3.141592...

$procB = ProcFactory::getProc('abs');
echo $proc->exec(-32);      // 32
```

#### function in user namespace
```php
<?php

use Prob\Handler\ProcFactory;

$proc = ProcFactory::getProc('testFunc', 'Apple\\Banana');
print_r($proc->exec('one', 'two', 'three'));        // Array ( 'three', 'two', 'one' )
```

someFunc.php
```php
<?php

namespace Apple\Banana;

function testFunc($a, $b, $c)
{
    return [$c, $b, $a];
}
```


### calling class method
```php
<?php

use Prob\Handler\ProcFactory;

$proc = ProcFactory::getProc('Orange.testFunc', 'Apple\\Banana');
echo $proc->exec(10, 5);            // 15
```

someClass.php
```php
<?php

namespace Apple\Banana;

class Orange
{
    public function testFunc($a, $b)
    {
        return $a + $b;
    }
}
```


### binding parameters
```php
<?php

use Prob\Handler\ProcFactory;
use Prob\Handler\ParameterMap;
use Prob\Handler\Parameter\Named;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\Parameter\TypedAndNamed;

use Apple\Banana\ValueObject;

$proc = ProcFactory::getProc('testFuncWithBinding', 'Apple\\Banana');

$vo = new ValueObject();
$vo->value = 'Welcome ';

$parameterMap = new ParameterMap();
$parameterMap->bindBy(new Named('arg3'), 'World!');
$parameterMap->bindBy(new Typed('array'), ['str' => 'Hello']);
$parameterMap->bindBy(new TypedAndNamed(ValueObject::class, 'arg1'), $vo);

// Welcome HelloWorld!
echo $proc->execWithParameterMap($parameterMap);
```

someFuncWithBinding.php
```php
<?php

namespace Apple\Banana;

function testFuncWithBinding(ValueObject $arg1, array $arg2, $arg3)
{
    return $arg1->value . $arg2['str'] . $arg3;
}

class ValueObject
{
    public $value;
}
```
