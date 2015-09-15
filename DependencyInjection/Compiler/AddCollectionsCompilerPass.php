<?php

namespace Limenius\Bundle\FilesystemRouterBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class AddCollectionsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitions = $container->getParameter('limenius_filesystem_router.collections');
        foreach ($definitions as $definition) {
            $container
                ->getDefinition('limenius_filesystem_router.route_provider')
                ->addMethodCall('addCollection', array($definition))
                ;
        }
    }
}
