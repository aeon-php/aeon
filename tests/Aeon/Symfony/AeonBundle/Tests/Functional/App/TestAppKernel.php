<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional\App;

use Aeon\Symfony\AeonBundle\AeonBundle;
use Aeon\Symfony\AeonBundle\Tests\Functional\App\Form\NotHolidaysFormType;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function holiday(Request $request) : Response
    {
        $form = $this->getContainer()->get('form.factory')->create(NotHolidaysFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response();
        }

        return new Response((string) $form->getErrors(true, false), 422);
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader) : void
    {
        $c->loadFromExtension('framework', [
            'secret' => 'S0ME_SECRET',
            'test' => $this->environment === 'test',
        ]);
        $c->loadFromExtension('aeon', [

        ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes) : void
    {
        $routes->add('/holiday', 'kernel::holiday');
    }
}
