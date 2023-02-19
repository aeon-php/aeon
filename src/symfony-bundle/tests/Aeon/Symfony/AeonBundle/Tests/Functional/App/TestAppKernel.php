<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional\App;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\RateLimiter\Storage\PSRCacheStorage;
use Aeon\Symfony\AeonBundle\AeonBundle;
use Aeon\Symfony\AeonBundle\RateLimiter\RateLimiters;
use Aeon\Symfony\AeonBundle\Tests\Functional\App\Form\NotHolidaysFormType;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class TestAppKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles() : array
    {
        return [
            new FrameworkBundle(),
            new AeonBundle(),
        ];
    }

    public function getCacheDir() : string
    {
        return \sys_get_temp_dir() . '/AeonBundle/cache';
    }

    public function getLogDir() : string
    {
        return \sys_get_temp_dir() . '/AeonBundle/logs';
    }

    public function holiday(Request $request, FormFactoryInterface $formFactory = null) : Response
    {
        $formFactory = $formFactory ?: $this->getContainer()->get('form.factory');
        $form = $formFactory->create(NotHolidaysFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response();
        }

        return new Response((string) $form->getErrors(true, false), 422);
    }

    public function throttle(Request $request) : Response
    {
        return new Response('', 200);
    }

    public function manualThrottle(Request $request) : Response
    {
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');
        $this->container->get(RateLimiters::class)->get('throttled_feature')->hit('test');

        return new Response('', 200);
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader) : void
    {
        $c->register('cache.psr.array.adapter', ArrayAdapter::class);
        $c->register('cache.psr.array', PSRCacheStorage::class)
            ->setArguments([new Reference('cache.psr.array.adapter'), new Reference(Calendar::class)]);

        $session = ['enabled' => true];

        if (BaseKernel::VERSION_ID > 50300) {
            $session['storage_factory_id'] = 'session.storage.factory.mock_file';
        } else {
            $session['storage_id'] = 'session.storage.filesystem';
        }
        $c->loadFromExtension('framework', [
            'secret' => 'S0ME_SECRET',
            'test' => $this->environment === 'test',
            'session' => $session,
        ]);
        $c->loadFromExtension('aeon', [
            'rate_limiter' => [
                [
                    'id' => 'throttled_feature',
                    'algorithm' => 'sliding_window',
                    'configuration' => [
                        'limit' => 5,
                        'time_window' => '10 seconds',
                        'storage_service_id' => 'cache.psr.array',
                    ],
                ],
            ],
            'request_throttling' => [
                'routes' => [
                    [
                        'route_name' => 'throttle',
                        'rate_limiter_id' => 'throttled_feature',
                        'methods' => ['POST'],
                        'request_identification_strategy' => [
                            'type' => 'header',
                            'configuration' => [
                                'header' => 'throttling-id',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
