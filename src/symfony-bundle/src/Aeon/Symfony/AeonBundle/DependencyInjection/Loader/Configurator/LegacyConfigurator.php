<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator as Symfony;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

/**
 * @deprecated Remove when dropping Symfony 4.4 support.
 */
final class LegacyConfigurator extends ContainerConfigurator
{
}

/**
 * @psalm-suppress UnusedParam
 * @psalm-suppress MixedInferredReturnType
 * @psalm-suppress MixedReturnStatement
 */
function service(string $id) : ReferenceConfigurator
{
    if (\function_exists('Symfony\Component\DependencyInjection\Loader\Configurator\service')) {
        return Symfony\service($id);
    }

    /** @phpstan-ignore-next-line */
    return Symfony\ref($id);
}
