<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Document;

class ContentDocument
{
    private $loaded = false;
    
    private $path;

    private $content;

    public function __construct($path)
    {
        $this->path = $path;
        $this->loaded = false;
    }

    public function getContent()
    {
        if (!$this->loaded) {
            $this->load();
        }
        return $this->content;
    }

    public function isLoaded()
    {
        return $this->loaded;
    }

    private function load()
    {
        $this->content = file_get_contents($this->path);
        $this->loaded = true;
    }
}
