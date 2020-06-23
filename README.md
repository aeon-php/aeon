# Aeon 

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/aeon-php/calendar-twig/license)](//packagist.org/packages/aeon-php/calendar-twig)
![Tests](https://github.com/aeon-php/calendar-twig/workflows/Tests/badge.svg?branch=1.x) 

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

Twig extension for Aeon

### Filters

* `aeon_date(string $format = null, string $timezone = null) : string` - formats Aeon `DateTime` object into string

### Functions

* `aeon_now(string $timezone = null) : Aeon\Calendar\Gregorian\DateTime` - creates new instance of Aeon `DateTime` from current time