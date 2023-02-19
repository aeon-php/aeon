<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\Type;

use Aeon\Symfony\AeonBundle\Form\DataTransformer\AeonDayToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

final class AeonDayType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent() : string
    {
        return DateType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() : string
    {
        return 'aeon_day';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        parent::buildForm($builder, $options);

        $builder->resetModelTransformers();
        $builder->addModelTransformer(new AeonDayToDateTimeTransformer());
    }
}
