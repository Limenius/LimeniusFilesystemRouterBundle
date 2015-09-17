<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Limenius\Bundle\FilesystemRouterBundle\DependencyInjection\LimeniusFilesystemRouterExtension;
use Symfony\Component\DependencyInjection\Reference;

class CmfRoutingExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return array(
            new LimeniusFilesystemRouterExtension(),
        );
    }
    public function testLoadDefault()
    {
        $cols = array('Docs' => array(
            'prefix' => 'docs',
            'path' => 'somepath'
        ));
        $this->load(array(
            'collections' => $cols
        ));
        $this->assertContainerBuilderHasService('limenius_filesystem_router.route_provider', 'Limenius\Bundle\FilesystemRouterBundle\Routing\FilesystemProvider');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall('limenius_filesystem_router.route_provider', 'addCollection', array(
            array(
                'prefix' => 'docs',
                'path' => 'somepath',
                'extensions_exposed' => array ('html')
            )
        ));
    }
}
