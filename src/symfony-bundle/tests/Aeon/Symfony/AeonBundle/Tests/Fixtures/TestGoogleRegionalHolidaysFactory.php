<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Fixtures;

use Aeon\Calendar\Holidays;
use Aeon\Calendar\Holidays\GoogleCalendarRegionalHolidays;
use Aeon\Calendar\HolidaysFactory;

final class TestGoogleRegionalHolidaysFactory implements HolidaysFactory
{
    private string $datasetPath;

    public function __construct(string $datasetPath)
    {
        $this->datasetPath = $datasetPath;
    }

    public function create(string $countryCode) : Holidays
    {
        return (new GoogleCalendarRegionalHolidays($countryCode))
            ->withDatasetPath($this->datasetPath);
    }
}
