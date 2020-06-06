# Aeon Calendar Holidays 

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

This is a brige between [Yasumi](https://yasumi.dev) library and [Aeon Calendar Holidays](https://github.com/aeon-php/calendar-holidays)
that brings `YasumiHolidays` instance of `Holidays`.


```php

$calendar = GregorianCalendar::UTC();

$holidays = YasumiHolidays::provider(
    \Yasumi\Provider\Poland::class, 
    YasumiHolidays::provider(Poland::class, $calendar->currentYear()->number())
);

if ($holidays->isHoliday($calendar->currentYear()->january()->firstDay())) {
    echo "Happy New Year!";
}
```

  