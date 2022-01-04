<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\Type;

use Aeon\Symfony\AeonBundle\Form\DataTransformer\AeonTimeZoneToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;

final class AeonTimeZoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent() : string
    {
        return TimezoneType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() : string
    {
        return 'aeon_timezone';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        parent::buildForm($builder, $options);

        $builder->resetModelTransformers();
        $builder->addModelTransformer(new AeonTimeZoneToDateTimeTransformer());
    }
}
