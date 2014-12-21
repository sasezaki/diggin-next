<?php
namespace Diggin\DocumentResolver;

use Diggin\DocumentResolver\DomDocumentFactory\FactorySelector as DomDocumentFactorySelector;
use Diggin\DocumentResolver\DomDocumentFactory\StrategyPluginManagerAwareTrait;
use Diggin\DocumentResolver\DomDocumentFactory\AssembleDomDocumentInterface;

class AssembleDocumentManager implements AssembleDomDocumentInterface
{
    use DomXpathFactoryAwareTrait;
    use StrategyPluginManagerAwareTrait {
        getStrategyPluginManager as public getDomDocumentFactoryStrategyPluginManager;
    }
    
    protected $domDocumentFactorySelector;

    /**
     * @see DomDocumentProviderInterface::getDomDocument()
     * @return \DomDocument
     */
    public function assembleDomDocument(Document $document)
    {
        if (!$document->getDomDocument()) {
                        
            $selector = $this->getDomDocumentFactorySelector();
            
            $select = $selector->select($document);
            $domDocumentFactory = $this->getDomDocumentFactoryStrategyPluginManager()->get($select);
                        
            $domDocument = $domDocumentFactory->assembleDomDocument($document);
            $document->setDomDocument($domDocument);            
        }

        return $document->getDomDocument();
    }


    /**
     * @return \DOMXPath
     */
    public function assembleDomXpath(Document $document)
    {
        $domXpath = $document->getDomXpath();
        if (!$domXpath) {
            $domDocument = $this->assembleDomDocument($document);
            $domXpathFactory = $this->getDomXpathFactory();
            $domXpath = $domXpathFactory->fromDomDocument($domDocument, $document->getDomXpathNamespaces());
            $document->setDomXpath($domXpath);
        }
    
        return $domXpath;
    }
    
    /**
     * @return DomDocumentFactorySelector
     */
    protected function getDomDocumentFactorySelector()
    {
        if (!$this->domDocumentFactorySelector instanceof DomDocumentFactorySelector) {
            $this->domDocumentFactorySelector = new DomDocumentFactorySelector();
        }
        
        return $this->domDocumentFactorySelector;
    }
}
