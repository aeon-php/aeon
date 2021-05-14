<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Form\Type;

use Aeon\Calendar\Gregorian\TimeZone;
use Aeon\Symfony\AeonBundle\Form\Type\AeonTimeZoneType;
use Symfony\Component\Form\Test\TypeTestCase;

final class AeonTimeZoneTypeTest extends TypeTestCase
{
    public const TESTED_TYPE = AeonTimeZoneType::class;

    public function test_submit_string() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, ['input' => 'string']);

        $form->submit('Europe/Warsaw');

        $timeZone = TimeZone::europeWarsaw();

        $this->assertEquals($timeZone, $form->getData());
    }

    public function test_submit_datetimezone() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, ['input' => 'datetimezone']);

        $form->submit('Europe/Warsaw');

        $timeZone = TimeZone::europeWarsaw();

        $this->assertEquals($timeZone, $form->getData());
    }

    public function test_submit_intltimezone() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, ['input' => 'intltimezone']);

        $form->submit('Europe/Warsaw');

        $timeZone = TimeZone::europeWarsaw();

        $this->assertEquals($timeZone, $form->getData());
    }

    public function test_submit_single_text_widget_with_set_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, ['input' => 'string']);

        $form->setData('Europe/Warsaw');

        $form->submit('Europe/Amsterdam');

        $timeZone = TimeZone::europeAmsterdam();

        $this->assertEquals($timeZone, $form->getData());
    }

    public function test_submit_single_text_widget_with_empty_value() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, ['input' => 'string']);

        $form->setData('Europe/Warsaw');

        $form->submit('');

        $this->assertNull($form->getData());
    }

    public function test_submit_string_with_non_empty_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, 'Europe/Warsaw');

        $form->submit('Europe/Warsaw');

        $timeZone = TimeZone::europeWarsaw();

        $this->assertEquals($timeZone, $form->getData());
    }

    protected function getTestedType() : string
    {
        return self::TESTED_TYPE;
    }
}
