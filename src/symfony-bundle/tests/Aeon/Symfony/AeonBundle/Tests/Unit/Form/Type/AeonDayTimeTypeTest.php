<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Unit\Form\Type;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Symfony\AeonBundle\Form\Type\AeonDayType;
use Symfony\Component\Form\Test\TypeTestCase;

final class AeonDayTimeTypeTest extends TypeTestCase
{
    public const TESTED_TYPE = AeonDayType::class;

    public function test_submit_single_text_widget() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
            'input' => 'string',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
            'input' => 'datetime',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_datetime_immutable() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_input_array() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
            'input' => 'array',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertObjectEquals($dateTime, $form->getData(), 'isEqual');
    }

    public function test_submit_single_text_widget_input_timestamp() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
            'input' => 'timestamp',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_with_set_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
        ]);

        $form->setData(Day::fromString('2020-01-01'));

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    public function test_submit_single_text_widget_with_empty_value() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, null, [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
        ]);

        $form->setData(Day::fromString('2020-01-01'));

        $form->submit('');

        $this->assertNull($form->getData());
    }

    public function test_submit_single_text_widget_with_non_empty_data() : void
    {
        $form = $this->factory->create(self::TESTED_TYPE, '2010-06-02', [
            'model_timezone' => 'UTC',
            'widget' => 'single_text',
        ]);

        $form->submit('2010-06-02');

        $dateTime = Day::fromString('2010-06-02');

        $this->assertEquals($dateTime, $form->getData());
    }

    protected function getTestedType() : string
    {
        return self::TESTED_TYPE;
    }
}
