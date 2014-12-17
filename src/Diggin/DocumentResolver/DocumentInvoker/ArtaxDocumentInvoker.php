<?php
namespace Diggin\DocumentResolver\DocumentInvoker;

use Amp\Artax\Client;
use Amp\Artax\Request;
use Diggin\DocumentResolver\Document;

class ArtaxDocumentInvoker
{
    protected $request;
    protected $client;
    
    public function getClient()
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client;
        }
        
        return $this->client;
    }
    
    public function getRequest()
    {
        if (!$this->request instanceof Request) {
            $this->request = new Request;
        }
    
        return $this->request;
    }
    
    protected function request($uri)
    {
        // needs for current "PHP Deprecated:  Amp\Promise::wait() is deprecated and scheduled for removal."
        $before = error_reporting(E_ALL &~E_USER_DEPRECATED);
        
        try {
            $request = clone $this->getRequest();
            $request->setUri($uri);
        
            $response = $this->getClient()->request($request)->wait();

            return $response;
        } catch (Exception $error) {
            echo $error;
        } finally {
            error_reporting($before);
        }
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