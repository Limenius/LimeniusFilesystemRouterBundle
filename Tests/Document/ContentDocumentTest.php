<?php

namespace Limenius\Bundle\FilesystemRouterBundle\Tests\Document;
use Limenius\Bundle\FilesystemRouterBundle\Document\ContentDocument;

class ContentDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testLazyLoad()
    {
        $document = new ContentDocument(__DIR__.'/../Resources/Fixtures/document.html');
        $this->assertFalse($document->isLoaded());
        $content = $document->getContent();
        $this->assertTrue($document->isLoaded());
        $this->assertEquals("hi there\n", $document->getContent());

    }

}
