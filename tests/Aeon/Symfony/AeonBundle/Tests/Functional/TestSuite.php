<?php

declare(strict_types=1);

namespace Aeon\Symfony\AeonBundle\Tests\Functional;

use Aeon\Symfony\AeonBundle\Tests\Functional\App\TestAppRouteCollectionKernel;
use Aeon\Symfony\AeonBundle\Tests\Functional\App\TestAppSymfonyLatestKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;

abstract class TestSuite extends WebTestCase
{
    protected static function getKernelClass() : string
    {
        if (\version_compare(Kernel::VERSION, '5.1.0') == -1) {
            return TestAppRouteCollectionKernel::class;
        }

        return TestAppSymfonyLatestKernel::class;
    }
}
