<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Form\Type;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Symfony\AeonBundle\Form\Type\AeonDateTimeType;
use Symfony\Component\Form\Test\TypeTestCase;

final class AeonDateTimeTypeTest extends TypeTestCase
{
    public const TESTED_TYPE = AeonDateTimeType::class;

    public function test_submit_single_text_widget_input_string() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'string',
        ]);

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 03:04:00 UTC');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'datetime',
        ]);

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 03:04:00 UTC');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime_immutable() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
        ]);

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 03:04:00 UTC');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_array() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'array',
        ]);

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 03:04:00 UTC');

        $this->assertObjectEquals($dateTime, $form->getData(), 'isEqual');
    }

    public function test_submit_single_text_widget_input_timestamp() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'timestamp',
        ]);

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 03:04:00 UTC');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_with_set_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'Europe/Warsaw',
            'widget' => 'single_text',
        ]);

        $form->setData(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $form->submit('2010-06-02 03:04:00 UTC');

        $dateTime = DateTime::fromString('2010-06-02 05:04:00 Europe/Warsaw');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_empty_value() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'Europe/Warsaw',
            'widget' => 'single_text',
        ]);

        $form->setData(DateTime::fromString('2020-01-01 00:00:00 UTC'));

        $form->submit('');

        $this->assertNull($form->getData());
    }

    protected function getTestedType()
    {
        return self::TESTED_TYPE;
    }
}
