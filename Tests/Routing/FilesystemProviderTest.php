<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Tests\Routing;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Limenius\Bundle\FilesystemRouterBundle\Routing\FilesystemProvider;

class FilesystemProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAllRoutes()
    {
        $provider = new FilesystemProvider();
        $this->assertCount(0, $provider->getRoutesByNames());
        $provider->addCollection([
            'path' => __DIR__.'/../Resources/Fixtures/Dir',
            'prefix' => '',
            'extensions_exposed' => ['html'],
        ]);
        $this->assertCount(1, $provider->getRoutesByNames());
    }

    public function testExtensionsExposed()
    {
        $provider = new FilesystemProvider();
        $provider->addCollection([
            'path' => __DIR__.'/../Resources/Fixtures/Dir',
            'prefix' => '',
            'extensions_exposed' => ['html', 'txt'],
        ]);
        $this->assertCount(2, $provider->getRoutesByNames());
        $paths = [];
        $patterns = [];
        foreach ($provider->getRoutesByNames()->getIterator() as $path => $route) {
            $paths[] = $path;
            $patterns[] = $route->getPattern();
        }
        ksort($paths);
        ksort($patterns);
        $this->assertSame(['one.html', 'two.txt'], $paths);
        $this->assertSame(['/one.html', '/two.txt'], $patterns);
    }

    public function testRouteNotFound()
    {
        $this->setExpectedException('Symfony\Component\Routing\Exception\RouteNotFoundException');
        $provider = new FilesystemProvider();
        $provider->addCollection([
            'path' => __DIR__.'/../Resources/Fixtures/Dir',
            'prefix' => '',
            'extensions_exposed' => ['html', 'txt'],
        ]);
        $provider->getRouteByName('jaar.html');

    }
    public function testGetRouteByName()
    {
        $provider = new FilesystemProvider();
        $provider->addCollection([
            'path' => __DIR__.'/../Resources/Fixtures/Dir',
            'prefix' => '',
            'extensions_exposed' => ['html', 'txt'],
        ]);
        $this->assertNotNull($provider->getRouteByName('/one.html'));

    }
}
