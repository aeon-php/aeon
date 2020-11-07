<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\Type;

use Aeon\Symfony\AeonBundle\Form\DataTransformer\AeonTimeToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

final class AeonTimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent() : string
    {
        return TimeType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() : string
    {
        return 'aeon_time';
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer(new AeonTimeToDateTimeTransformer());
    }
}
