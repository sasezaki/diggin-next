<?php
namespace Diggin\DocumentResolver;

use Diggin\DocumentResolver\DocumentInvoker\ArtaxResponseDocumentInvoker;
use Amp\Artax\Response;

class ArtaxResponseDocumentResolver
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
    public function __construct(AssembleDocumentManager $assembleManager = null, callable $documentInvoker = null)
    {
        $this->assembleManager = ($assembleManager) ?: new AssembleDocumentManager;
        $this->documentInvoker = ($documentInvoker) ?: new ArtaxResponseDocumentInvoker();
    }
    
    /**
     * @return Document
     */
    public function getDomDocument(Response $response)
    {
        $document = call_user_func($this->documentInvoker, $response);
        $this->assembleManager->assembleDomDocument($document);
        
        return $document->getDomDocument();
    }
    
    /**
     * @return \DOMXPath
     */
    public function getDomXpath(Response $response)
    {
        $document = call_user_func($this->documentInvoker, $response);
        $this->assembleManager->assembleDomXpath($document);
    
        return $document->getDomXpath();
    }
    
    public function getAssembleDocumentManager()
    {
        return $this->assembleManager;
    }
}
