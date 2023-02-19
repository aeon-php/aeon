<?php

declare(strict_types=1);

namespace Aeon\Calendar\Holidays;

use Aeon\Calendar\Exception\InvalidArgumentException;
use Yasumi\Provider\Australia;
use Yasumi\Provider\Austria;
use Yasumi\Provider\Belgium;
use Yasumi\Provider\Bosnia;
use Yasumi\Provider\Brazil;
use Yasumi\Provider\Canada;
use Yasumi\Provider\Croatia;
use Yasumi\Provider\CzechRepublic;
use Yasumi\Provider\Denmark;
use Yasumi\Provider\Estonia;
use Yasumi\Provider\Finland;
use Yasumi\Provider\France;
use Yasumi\Provider\Germany;
use Yasumi\Provider\Greece;
use Yasumi\Provider\Hungary;
use Yasumi\Provider\Ireland;
use Yasumi\Provider\Italy;
use Yasumi\Provider\Japan;
use Yasumi\Provider\Latvia;
use Yasumi\Provider\Lithuania;
use Yasumi\Provider\Luxembourg;
use Yasumi\Provider\Netherlands;
use Yasumi\Provider\NewZealand;
use Yasumi\Provider\Norway;
use Yasumi\Provider\Poland;
use Yasumi\Provider\Portugal;
use Yasumi\Provider\Romania;
use Yasumi\Provider\Russia;
use Yasumi\Provider\Slovakia;
use Yasumi\Provider\SouthAfrica;
use Yasumi\Provider\SouthKorea;
use Yasumi\Provider\Spain;
use Yasumi\Provider\Sweden;
use Yasumi\Provider\Switzerland;
use Yasumi\Provider\Ukraine;
use Yasumi\Provider\UnitedKingdom;
use Yasumi\Provider\USA;

final class Providers
{
    public const AU = Australia::class;

    public const AT = Austria::class;

    public const BE = Belgium::class;

    public const BA = Bosnia::class;

    public const BR = Brazil::class;

    public const CA = Canada::class;

    public const HR = Croatia::class;

    public const CZ = CzechRepublic::class;

    public const DK = Denmark::class;

    public const EE = Estonia::class;

    public const FI = Finland::class;

    public const FR = France::class;

    public const DE = Germany::class;

    public const GR = Greece::class;

    public const HU = Hungary::class;

    public const IE = Ireland::class;

    public const IT = Italy::class;

    public const JP = Japan::class;

    public const LV = Latvia::class;

    public const LT = Lithuania::class;

    public const LU = Luxembourg::class;

    public const NL = Netherlands::class;

    public const NZ = NewZealand::class;

    public const NO = Norway::class;

    public const PL = Poland::class;

    public const PT = Portugal::class;

    public const RO = Romania::class;

    public const RU = Russia::class;

    public const SK = Slovakia::class;

    public const ZA = SouthAfrica::class;

    public const KR = SouthKorea::class;

    public const ES = Spain::class;

    public const SE = Sweden::class;

    public const CH = Switzerland::class;

    public const UA = Ukraine::class;

    public const GB = UnitedKingdom::class;

    public const US = USA::class;

    /**
     * @return array<string>
     */
    public static function all() : array
    {
        return [
            'AU' => self::AU,
            'AT' => self::AT,
            'BE' => self::BE,
            'BA' => self::BA,
            'BR' => self::BR,
            'CA' => self::CA,
            'HR' => self::HR,
            'CZ' => self::CZ,
            'DK' => self::DK,
            'EE' => self::EE,
            'FI' => self::FI,
            'FR' => self::FR,
            'DE' => self::DE,
            'GR' => self::GR,
            'HU' => self::HU,
            'IE' => self::IE,
            'IT' => self::IT,
            'JP' => self::JP,
            'LV' => self::LV,
            'LT' => self::LT,
            'LU' => self::LU,
            'NL' => self::NL,
            'NZ' => self::NZ,
            'NO' => self::NO,
            'PL' => self::PL,
            'PT' => self::PT,
            'RO' => self::RO,
            'RU' => self::RU,
            'SK' => self::SK,
            'ZA' => self::ZA,
            'KR' => self::KR,
            'ES' => self::ES,
            'SE' => self::SE,
            'CH' => self::CH,
            'UA' => self::UA,
            'GB' => self::GB,
            'US' => self::US,
        ];
    }

    public static function fromCountryCode(string $countryCode) : string
    {
        $upperCaseCountryCode = \strtoupper($countryCode);

        if (!\array_key_exists($upperCaseCountryCode, self::all())) {
            throw new InvalidArgumentException('Country code ' . $upperCaseCountryCode . ' is ont assigned to any Yasumi provider.');
        }

        return self::all()[$upperCaseCountryCode];
    }
}
