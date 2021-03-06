<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Tests\DependencyInjection;

use Limenius\Bundle\FilesystemRouterBundle\DependencyInjection\Configuration;
use Limenius\Bundle\FilesystemRouterBundle\DependencyInjection\LimeniusFilesystemRouterExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    protected function getContainerExtension()
    {
        return new LimeniusFilesystemRouterExtension();
    }
    protected function getConfiguration()
    {
        return new Configuration();
    }
    public function testSupportsAllConfigOptions()
    {
        $expectedConfiguration = array(
            'collections' => array(
                'Docs' => array(
                    'prefix' => 'docs',
                    'path' => 'somepath',
                    'extensions_exposed' => array('html')
                ),
                'Another' => array(
                    'path' => 'someotherpath',
                    'extensions_exposed' => array('js', 'txt')
                ),
            ),
        );
        $fixture = __DIR__.'/../Resources/Fixtures/config.yml';
        $this->assertProcessedConfigurationEquals($expectedConfiguration, array($fixture));
    }
}
