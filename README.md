# Aeon 

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/aeon-php/process/license)](//packagist.org/packages/aeon-php/process)
![Tests](https://github.com/aeon-php/process/workflows/Tests/badge.svg?branch=1.x) 

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

Retry operations that might fail like for example http requests, with different 
delay modifiers. 

```php
<?php

use Aeon\Calendar\TimeUnit;
use Aeon\Retry\Execution;
use function Aeon\Retry\retry;

$result = retry(function (Execution $execution) {
    $random = \random_int(1, 3);

    if ($random === 2) {
        throw new \RuntimeException('exception');
    }

    return $random;
}, 3, TimeUnit::seconds(3));
```

Object implementation that multiplies delays by retry number after each failure.

```php
<?php

use Aeon\Calendar\System\SystemProcess;use Aeon\Calendar\TimeUnit;
use Aeon\Retry\DelayModifier\RetryMultiplyDelay;use Aeon\Retry\Execution;
use Aeon\Retry\Retry;

return (new Retry(
        SystemProcess::current(),
        5,
        TimeUnit::milliseconds(100)
    ))->modifyDelay(
        new RetryMultiplyDelay()
    )->execute(function (Execution $execution) {
        $random = \random_int(1, 3);
   
        if ($random === 2) {
            throw new \RuntimeException('exception');
        }
   
        return $random;
    });
```
