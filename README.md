# Aeon Calendar Doctrine

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon)

## Usage

```php
use Doctrine\DBAL\Types\Type;
use Aeon\Calendar\Doctrine\Gregorian\DateType;
use Aeon\Calendar\Doctrine\Gregorian\DateTimeType;
use Aeon\Calendar\Doctrine\Gregorian\DateTimeTzType;

Type::addType(DateType::NAME, DateType::class); // aeon_date
Type::addType(DateTimeType::NAME, DateTimeType::class); // aeon_datetime
Type::addType(DateTimeTzType::NAME, DateTimeTzType::class); // aeon_datetime_tz
``` 

```yaml
# config/packages/doctrine.yaml

doctrine:
    dbal:
        types:
            aeon_date: Aeon\Calendar\Doctrine\Gregorian\DateType
            aeon_datetime: Aeon\Calendar\Doctrine\Gregorian\DateTimeType
            aeon_datetime_tz: Aeon\Calendar\Doctrine\Gregorian\DateTimeTzType
```
