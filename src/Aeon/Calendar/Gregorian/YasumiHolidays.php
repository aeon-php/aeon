<?php

declare(strict_types=1);

namespace Aeon\Calendar\Gregorian;

use Aeon\Calendar\Exception\HolidayException;
use Aeon\Calendar\Exception\InvalidArgumentException;
use Aeon\Calendar\Gregorian\Holidays\Holiday as AeonHoliday;
use Aeon\Calendar\Gregorian\Holidays\HolidayLocaleName;
use Aeon\Calendar\Gregorian\Holidays\HolidayName;
use Yasumi\Exception\ProviderNotFoundException;
use Yasumi\Holiday;
use Yasumi\Provider\AbstractProvider;
use Yasumi\Yasumi;

/**
 * @psalm-immutable
 */
final class YasumiHolidays implements Holidays
{
    /** @phpstan-ignore-next-line */
    private ?AbstractProvider $yasumi;

    private string $providerClass;

    private int $year;

    private function __construct(string $providerClass, int $year)
    {
        if (!\class_exists($providerClass)) {
            throw new InvalidArgumentException($providerClass . ' is not valid Yasumi provider class.');
        }

        $this->yasumi = null;
        $this->providerClass = $providerClass;
        $this->year = $year;
    }

    /**
     * @psalm-pure
     */
    public static function provider(string $providerClass, int $year) : self
    {
        return new self($providerClass, $year);
    }

    public function isHoliday(Day $day) : bool
    {
        /** @psalm-suppress ImpureMethodCall */
        return $this->yasumi()->isHoliday($day->toDateTimeImmutable());
    }

    public function holidaysAt(Day $day) : array
    {
        return \array_values(
            \array_map(
                function (Holiday $holiday) : AeonHoliday {
                    /** @psalm-suppress ImpureMethodCall */
                    return new AeonHoliday(
                        Day::fromDateTime($holiday),
                        new HolidayName(
                            new HolidayLocaleName('us', $holiday->getName())
                        )
                    );
                },
                \array_filter(
                    $this->yasumi()->getHolidays(),
                    function (Holiday $holiday) use ($day) : bool {
                        return Day::fromDateTime($holiday)->isEqual($day);
                    }
                )
            )
        );
    }

    /** @phpstan-ignore-next-line */
    private function yasumi() : AbstractProvider
    {
        if ($this->yasumi instanceof AbstractProvider) {
            return $this->yasumi;
        }

        try {
            /**
             * @psalm-suppress InaccessibleProperty
             * @psalm-suppress ImpureMethodCall
             */
            $this->yasumi = Yasumi::create($this->providerClass, $this->year, 'en_US');

            return $this->yasumi;
        } catch (ProviderNotFoundException $providerNotFoundException) {
            throw new HolidayException('Yasumi provider ' . $this->providerClass . ' does not exists', 0, $providerNotFoundException);
        }
    }
}
