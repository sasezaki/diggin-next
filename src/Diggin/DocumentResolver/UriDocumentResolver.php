<?php
namespace Diggin\DocumentResolver;

class UriDocumentResolver extends AbstractDocumentResolver
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var Document
     */
    private $document;
    
    /**
     * @var callable
     */
    private $documentInvoker;

    public function __construct($uri, $documentInvoker = null)
    {
        $this->uri = $uri;
        $this->documentInvoker = is_callable($documentInvoker) ? $documentInvoker : function () {
            $content = file_get_contents($this->uri);
            $document = new \Diggin\DocumentResolver\Document();
            $document->setContent($content);
            $document->setUri($this->uri);
            
            return $document;
        };
        
        $this->attachStandardEvents();
    }
    
    public function attachStandardEvents()
    {
        $em = $this->getEventManager();
        $em->attach('detect.pre', function(\Zend\EventManager\Event $e){
            $params = $e->getParams();
            
            $document = $params[0];

            /** @var \Diggin\DocumentResolver\Document $document */
            $content = $document->getContent();
            $document->setContent($content);
            return $document;
        });
    }
    
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    
    /**
     * @return Document
     */
    protected function getDocument()
    {
        if (!$this->document instanceof Document) {
            $documentInvoker = $this->documentInvoker;
            $this->document = call_user_func($documentInvoker);
        }
        
        return $this->document;
    }   
}
