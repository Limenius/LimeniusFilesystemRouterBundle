<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController
{

    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function indexAction(Request $request, $contentDocument, $contentTemplate)
    {
        return $this->templating->renderResponse($contentTemplate, array(
            'contentDocument' => $contentDocument
        ));
    }
}
