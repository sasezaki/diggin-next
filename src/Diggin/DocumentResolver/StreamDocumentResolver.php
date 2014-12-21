<?php
namespace Diggin\DocumentResolver;

class StreamDocumentResolver
{
    use DomXpathFactoryAwareTrait;

    /**
     * @var AssembleDocumentManager
     */
    private $assembleManager;
  
    /**
     * @var callable
     */
    private $documentInvoker;

    /**
     * @param string $uri
     * @param callable|null $documentInvoker
     */
    public function __construct(AssembleDocumentManager $assembleManager = null, $documentInvoker = null)
    {
        $this->assembleManager = ($assembleManager) ?: new AssembleDocumentManager;
        $this->documentInvoker = is_callable($documentInvoker) ? $documentInvoker :
         function ($uri, $use_include_path = false, $context = null) {
            $content = file_get_contents($uri, $use_include_path, $context);
            
            $document = new \Diggin\DocumentResolver\Document();
            $document->setContent($content); // todo move setContent -> setContentFactory(callble)
            $document->setUri($uri);
            //$document->getHttpMessage()->setHeader($header, $value);
            
            return $document;
        };
    }
    
    /**
     * @return Document
     */
    public function getDomDocument($uri, $use_include_path = false , $context = null)
    {
        $documentInvoker = $this->documentInvoker;
        $document = call_user_func($documentInvoker, $uri, $use_include_path, $context);
        $this->assembleManager->assembleDomDocument($document);
        
        return $document->getDomDocument();
    }
    
    /**
     * @return \DOMXPath
     */
    public function getDomXpath($uri, $use_include_path = false , $context = null)
    {
        $documentInvoker = $this->documentInvoker;
        $document = call_user_func($documentInvoker, $uri, $use_include_path, $context);
        $this->assembleManager->assembleDomXpath($document);
    
        return $document->getDomXpath();
    }
    
    public function getAssembleDocumentManager()
    {
        return $this->assembleManager;
    }
}
