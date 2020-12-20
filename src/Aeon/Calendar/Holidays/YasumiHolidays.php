<?php

declare(strict_types=1);

namespace Aeon\Calendar\Holidays;

use Aeon\Calendar\Exception\HolidayException;
use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Holidays;
use Aeon\Calendar\Holidays\Holiday as AeonHoliday;
use Yasumi\Exception\ProviderNotFoundException;
use Yasumi\Holiday;
use Yasumi\Provider\AbstractProvider;
use Yasumi\Yasumi;

/**
 * @psalm-immutable
 */
final class YasumiHolidays implements Holidays
{
    /**
     * @var array<int, AbstractProvider>
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
        /** @psalm-suppress ImpureMethodCall */
        return $this->yasumi($day->year()->number())->isHoliday($day->toDateTimeImmutable());
    }

    public function holidaysAt(Day $day) : array
    {
        /** @psalm-suppress ImpureFunctionCall */
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
                        return Day::fromDateTime($holiday)->isEqual($day);
                    }
                )
            )
        );
    }

    /** @phpstan-ignore-next-line */
    private function yasumi(int $year) : AbstractProvider
    {
        if (isset($this->yasumi[$year])) {
            return $this->yasumi[$year];
        }

        try {
            /**
             * @psalm-suppress InaccessibleProperty
             * @psalm-suppress ImpureMethodCall
             */
            $this->yasumi[$year] = Yasumi::create($this->providerClass, $year, 'en_US');

            return $this->yasumi[$year];
        } catch (ProviderNotFoundException $providerNotFoundException) {
            throw new HolidayException('Yasumi provider ' . $this->providerClass . ' does not exists', 0, $providerNotFoundException);
        }
    }
}
