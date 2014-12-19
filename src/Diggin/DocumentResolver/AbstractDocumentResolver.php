<?php
namespace Diggin\DocumentResolver;

use Diggin\DocumentResolver\DomDocumentFactory\FactorySelector as DomDocumentFactorySelector;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{
    use DomXpathFactoryAwareTrait;

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
                        
            $selector = $this->getDomDocumentFactorySelector($document);
            $domDocumentFactory = $selector->select();
            $domDocument = $domDocumentFactory->getDomDocument();
            $document->setDomDocument($domDocumentFactory->getDomDocument());            
        }

        return $document->getDomDocument();
    }
    
    protected function getDomDocumentFactorySelector(Document $document)
    {
        return new DomDocumentFactorySelector($document);
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
