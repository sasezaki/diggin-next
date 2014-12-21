<?php
namespace Diggin\DocumentResolver\DocumentInvoker;

use Amp\Artax\Client;
use Amp\Artax\Request;
use Diggin\DocumentResolver\Document;

class ArtaxClientDocumentInvoker
{
    protected $defaultRequest;
    protected $client;
    
    public function getClient()
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client;
        }
        
        return $this->client;
    }
    
    public function getDefaultRequest()
    {
        if (!$this->defaultRequest instanceof Request) {
            $this->defaultRequest = new Request;
        }
    
        return $this->defaultRequest;
    }
    
    protected function request($uri)
    {
        $request = clone $this->getDefaultRequest();
        $request->setUri($uri);
    
        /** @var \Amp\Promise $promise */
        $promise = $this->getClient()->request($request);            
        $response = \Amp\wait($promise);

        return $response;
    }
    
    public function __invoke($uri)
    {
        /** @var \Amp\Artax\Response $response */
        $response = $this->request($uri);
                 
        $document = new Document();
        $document->setUri($uri);
        $document->setContent($response->getBody());
        $document->getHttpMessage()->setHeader('Content-Type', $response->getHeader('Content-Type')[0]);
        
        return $document;
    }
} 