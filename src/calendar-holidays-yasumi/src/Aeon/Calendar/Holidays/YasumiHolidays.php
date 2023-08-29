<?php

declare(strict_types=1);

namespace Aeon\Calendar\Holidays;

use Aeon\Calendar\Exception\HolidayException;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\TimePeriod;
use Aeon\Calendar\Holidays;
use Aeon\Calendar\Holidays\Holiday as AeonHoliday;
use Yasumi\Exception\ProviderNotFoundException;
use Yasumi\Holiday;
use Yasumi\ProviderInterface;
use Yasumi\Yasumi;

/**
 * @psalm-immutable
 */
final class YasumiHolidays implements Holidays
{
    /**
     * @var array<int, ProviderInterface>
     */
    private array $yasumi;

    private string $providerClass;

    public function __construct(string $countryCode)
    {
        $this->yasumi = [];
        $this->providerClass = Providers::fromCountryCode($countryCode);
    }

    public function isHoliday(Day $day) : bool
    {
        /**
         * @psalm-suppress ImpureMethodCall
         * @psalm-suppress UndefinedInterfaceMethod
         */
        return $this->yasumi($day->year()->number())->isHoliday($day->toDateTimeImmutable());
    }

    /**
     * @throws HolidayException
     * @throws \Aeon\Calendar\Exception\InvalidArgumentException
     * @throws \Yasumi\Exception\MissingTranslationException
     *
     * @return AeonHoliday[]
     */
    public function in(TimePeriod $period) : array
    {
        $holidays = [];

        foreach ($period->start()->year()->until($period->end()->year(), Interval::closed()) as $year) {
            /**
             * @psalm-suppress UndefinedInterfaceMethod
             * @psalm-suppress ImpureMethodCall
             */
            foreach ($this->yasumi($year->number())->getHolidays() as $yasumiHoliday) {
                /** @psalm-suppress ImpureMethodCall */
                $holiday = new AeonHoliday(
                    Day::fromDateTime($yasumiHoliday),
                    new HolidayName(
                        new HolidayLocaleName('us', $yasumiHoliday->getName())
                    )
                );

                if ($holiday->day()->isAfterOrEqualTo($period->start()->day()) && $holiday->day()->isBeforeOrEqualTo($period->end()->day())) {
                    $holidays[] = $holiday;
                }
            }
        }

        return $holidays;
    }

    /**
     * @psalm-suppress ImpureMethodCall
     */
    public function holidaysAt(Day $day) : array
    {
        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress UndefinedInterfaceMethod
         */
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
                    $this->yasumi($day->year()->number())->getHolidays(),
                    function (Holiday $holiday) use ($day) : bool {
                        return Day::fromDateTime($holiday)->isEqualTo($day);
                    }
                )
            )
        );
    }

    /**
     * @param int $year
     *
     * @throws HolidayException
     *
     * @return ProviderInterface
     */
    private function yasumi(int $year) : ProviderInterface
    {
        if (\array_key_exists($year, $this->yasumi)) {
            return $this->yasumi[$year];
        }

        try {
            /**
             * @psalm-suppress InaccessibleProperty
             * @psalm-suppress ImpureMethodCall
             */
            $this->yasumi[$year] = Yasumi::create($this->providerClass, $year, 'en_US');

            return $this->yasumi[$year];
        } catch (ProviderNotFoundException | \ReflectionException $providerNotFoundException) {
            throw new HolidayException('Yasumi provider ' . $this->providerClass . ' does not exists', 0, $providerNotFoundException);
        }
    }
}
