<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional\App;

use Aeon\Symfony\AeonBundle\AeonBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class TestAppKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new AeonBundle(),
        ];
    }

    public function getCacheDir()
    {
        return \sys_get_temp_dir() . '/AeonBundle/cache';
    }

    public function getLogDir()
    {
        return \sys_get_temp_dir() . '/AeonBundle/logs';
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader) : void
    {
        $c->loadFromExtension('framework', [
            'secret' => 'S0ME_SECRET',
        ]);
        $c->loadFromExtension('aeon', [

        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes) : void
    {
    }
}
