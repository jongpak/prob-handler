# Prob/Handler
*A simple library for calling function and method*

[![Build Status](https://travis-ci.org/jongpak/prob-handler.svg?branch=master)](https://travis-ci.org/jongpak/prob-handler)

## Usage

### calling function
#### function in global namespace
```php
<?php

use Prob\Handler;

$procA = new Proc('pi');
echo $proc->exec();         // 3.141592...

$procB = new Proc('abs');
echo $proc->exec(-32);      // 32
```

#### function in user namespace
```php
<?php

use Prob\Handler;

$proc = new Proc('testFunc', 'Apple\\Banana');
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

use Prob\Handler;

$proc = new Proc('Orange.testFunc', 'Apple\\Banana');
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
