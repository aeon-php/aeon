<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Form\Type;

use Aeon\Calendar\Gregorian\Time;
use Aeon\Symfony\AeonBundle\Form\Type\AeonTimeType;
use Symfony\Component\Form\Test\TypeTestCase;

final class AeonTimeTypeTest extends TypeTestCase
{
    public const TESTED_TYPE = AeonTimeType::class;

    public function test_submit_single_text_widget_input_string() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'string',
        ]);

        $form->submit('13:00');

        $dateTime = Time::fromString('13:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'datetime',
        ]);

        $form->submit('13:00');

        $dateTime = Time::fromString('13:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime_immutable() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
        ]);

        $form->submit('13:00');

        $dateTime = Time::fromString('13:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_array() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'array',
        ]);

        $form->submit('13:00');

        $dateTime = Time::fromString('13:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_timestamp() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
            'input' => 'timestamp',
        ]);

        $form->submit('13:00');

        $dateTime = Time::fromString('13:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_with_set_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
        ]);

        $form->setData(Time::fromString('13:00'));

        $form->submit('18:00');

        $dateTime = Time::fromString('18:00');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_with_empty_value() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'widget' => 'single_text',
        ]);

        $form->setData(Time::fromString('13:00'));

        $form->submit('');

        $this->assertNull($form->getData());
    }

    protected function getTestedType()
    {
        return self::TESTED_TYPE;
    }
}
