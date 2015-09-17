<?php

namespace Limenius\Bundle\FilesystemRouterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class LimeniusFilesystemRouterExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $collections = $config['collections'];
        foreach ($collections as $collection) {
            $container
                ->getDefinition('limenius_filesystem_router.route_provider')
                ->addMethodCall('addCollection', array($collection))
                ;
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $prependConfig = array('dynamic' =>
            array('generic_controller' => 'cmf_content.controller:indexAction'));

        $container->prependExtensionConfig('cmf_routing', $prependConfig);
    }
}
