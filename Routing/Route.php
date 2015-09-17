<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Limenius\Bundle\FilesystemRouterBundle\Document\ContentDocument;

class Route extends SymfonyRoute implements RouteObjectInterface
{
    private $content;

    /**
     * Unique key of this route
     *
     * @var string
     */
    private $key;

    public function __construct(array $options = array())
    {
        $this->setDefaults(array('type' => 'filesystem_route'));
        $this->setOptions($options);
    }

    public function setContent(ContentDocument $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setRouteKey($key)
    {
        $this->key = $key;
    }

    public function getRouteKey()
    {
        return $this->key;
    }
}
