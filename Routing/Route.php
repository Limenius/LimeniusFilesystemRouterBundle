<?php

namespace Limenius\FilesystemRouterBundle\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

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
        //$this->setRequirements(array());
        $this->setOptions($options);
        //if ($this->getOption('add_format_pattern')) {
        //    $this->setDefault('_format', 'html');
        //    $this->setRequirement('_format', 'html');
        //}
    }

    public function setContent($content)
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
