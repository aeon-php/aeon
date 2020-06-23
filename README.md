# Aeon 

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/aeon-php/calendar-holidays-yasumi/license)](//packagist.org/packages/aeon-php/calendar-holidays-yasumi)
![Tests](https://github.com/aeon-php/calendar-holidays-yasumi/workflows/Tests/badge.svg?branch=1.x) 

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

This is a brige between [Yasumi](https://yasumi.dev) library and [Aeon Calendar Holidays](https://github.com/aeon-php/calendar-holidays)
that brings `YasumiHolidays` instance of `Holidays`.


```php
<?php
use Aeon\Calendar\Gregorian\GregorianCalendar;
use Aeon\Calendar\Gregorian\YasumiHolidays;
use Yasumi\Provider\Poland;

$calendar = GregorianCalendar::UTC();

$holidays = YasumiHolidays::provider(Poland::class, $calendar->currentYear()->number());

if ($holidays->isHoliday($calendar->currentYear()->january()->firstDay())) {
    echo "Happy New Year!";
}
```

  