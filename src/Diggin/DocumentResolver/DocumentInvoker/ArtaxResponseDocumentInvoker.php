<?php
namespace Diggin\DocumentResolver\DocumentInvoker;

use Diggin\DocumentResolver\Document;
use Amp\Artax\Response;

class ArtaxResponseDocumentInvoker
{
    private $response;
    
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    
    public function __invoke($uri)
    {
        $document = new Document();
        $document->setUri($uri);
        $document->setContent($this->response->getBody());
        $document->getHttpMessage()->setHeader('Content-Type', $this->response->getHeader('Content-Type')[0]);
        
        return $document;
    }
} 