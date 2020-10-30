<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\DependencyInjection;

use Aeon\Calendar\Gregorian\TimeZone;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('aeon');
        /**
         * @psalm-suppress MixedAssignment
         * @phpstan-ignore-next-line
         */
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('aeon');

        $rootNode
            ->children()
                ->scalarNode('calendar_timezone')->defaultValue(TimeZone::UTC)->end()
                ->scalarNode('ui_timezone')->defaultValue(TimeZone::UTC)->end()
                ->scalarNode('ui_datetime_format')->defaultValue('Y-m-d H:i:s')->end()
                ->scalarNode('ui_date_format')->defaultValue('Y-m-d')->end()
                ->scalarNode('ui_time_format')->defaultValue('H:i:s')->end()
            ->end();

        return $treeBuilder;
    }
}
