<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Form\Type;

use Aeon\Symfony\AeonBundle\Form\DataTransformer\AeonDateTimeToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

final class AeonDateTimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent() : string
    {
        return DateTimeType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() : string
    {
        return 'aeon_datetime';
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer(new AeonDateTimeToDateTimeTransformer());
    }
}
