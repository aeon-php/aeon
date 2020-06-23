# Aeon 

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/aeon-php/process/license)](//packagist.org/packages/aeon-php/process)
![Tests](https://github.com/aeon-php/process/workflows/Tests/badge.svg?branch=1.x) 

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

This repository provides literally one function that makes it easier to pause your process for a certain time.

Let say you would like to sleep for 200 milliseconds. Instead of using usleep and multiplication of 200 by... 
exactly, how much? Just use Aeon sleep. 

```php
<?php

use Aeon\Calendar\TimeUnit;
use function \Aeon\Calendar\System\sleep;

sleep(TimeUnit::milliseconds(200));
```

Thanks to [TimeUnit](https://github.com/aeon-php/calendar/blob/master/src/Aeon/Calendar/TimeUnit.php) you can 
sleep with any precision you need. 

Sleeping in tests might be tricky, one might put sleep in the test case but who likes slow test suite? 
Instead of using function go with `SystemProcess` and expect instance of `Process`
in your [system under test](https://en.wikipedia.org/wiki/System_under_test).  

```php
<?php

use Aeon\Calendar\System\SystemProcess;
use Aeon\Calendar\TimeUnit;

$process = SystemProcess::current();

$process->sleep(TimeUnit::milliseconds(200));
```