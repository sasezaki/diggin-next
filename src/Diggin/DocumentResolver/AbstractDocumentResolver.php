<?php
namespace Diggin\DocumentResolver;

use Diggin\DocumentResolver\DomDocumentFactory\FactorySelector as DomDocumentFactorySelector;
use Diggin\DocumentResolver\DomDocumentFactory\FormattingHtmlStrategy;
use Diggin\DocumentResolver\DomDocumentFactory\StrategyPluginManagerAwareTrait;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{
    use DomXpathFactoryAwareTrait;
    use StrategyPluginManagerAwareTrait {
        getStrategyPluginManager as public getDomDocumentFactoryStrategyPluginManager;
    }
    
    protected $domDocumentFactorySelector;

    /**
     * @return Document
     */
    abstract protected function getDocument();
    
    /**
     * A proxy to Document's getContent()
     * 
     * @return string
     */
    public function getContent()
    {
        $this->getDocument()->getContent();
    }

    /**
     * @see DomDocumentProviderInterface::getDomDocument()
     * @return \DomDocument
     */
    public function getDomDocument()
    {
        $document = $this->getDocument();
        if (!$document->getDomDocument()) {
                        
            $selector = $this->getDomDocumentFactorySelector();
            
            $select = $selector->select($document);
            $domDocumentFactory = $this->getDomDocumentFactoryStrategyPluginManager()->get($select);
                        
            $domDocument = $domDocumentFactory->assemble($document);
            $document->setDomDocument($domDocument);            
        }

        return $document->getDomDocument();
    }
    
    protected function getDomDocumentFactorySelector()
    {
        if (!$this->domDocumentFactorySelector instanceof DomDocumentFactorySelector) {
            $this->domDocumentFactorySelector = new DomDocumentFactorySelector();
        }
        
        return $this->domDocumentFactorySelector;
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        $document = $this->getDocument();
        $domXpath = $document->getDomXpath();
        if (!$domXpath) {
            $domDocument = $this->getDomDocument();
            $domXpathFactory = $this->getDomXpathFactory();
            $domXpath = $domXpathFactory->fromDomDocument($domDocument, $document->getDomXpathNamespaces());
            $document->setDomXpath($domXpath);
        }

        return $domXpath;
    }
}
