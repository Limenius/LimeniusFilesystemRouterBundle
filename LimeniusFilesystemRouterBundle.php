<?php

namespace Limenius\Bundle\FilesystemRouterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Limenius\Bundle\FilesystemRouterBundle\DependencyInjection\Compiler\AddCollectionsCompilerPass;

class LimeniusFilesystemRouterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddCollectionsCompilerPass());
    }
}
