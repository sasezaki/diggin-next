<?php
namespace Diggin\DocumentResolver\DocumentInvoker;

use Diggin\DocumentResolver\Document;
use Amp\Artax\Response;

class ArtaxResponseDocumentInvoker
{
    public function __invoke(Response $response)
    {
        $document = new Document();
        $document->setUri($response->getRequest()->getUri());
        $document->setContent($response->getBody());
        $document->getHttpMessage()->setHeader('Content-Type', $response->getHeader('Content-Type')[0]);
        
        return $document;
    }
} 