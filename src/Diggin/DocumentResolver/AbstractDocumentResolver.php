<?php
namespace Diggin\DocumentResolver;

use Diggin\HttpCharset\HttpCharsetManagerAwareTrait;
use Diggin\HttpCharset\Filter as HttpCharsetFilter;
use Diggin\HttpCharset\CharsetEncoding;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;
use Diggin\DocumentResolver\DomDocumentFactory\FactorySelector as DomDocumentFactorySelector;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{    
    use HttpCharsetManagerAwareTrait;
    use HtmlFormatterAwareTrait;

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
            
            //$selector = $this->getDomDocumentFactoryStrategySelector($document);
            
            $selector = $this->getDomDocumentFactorySelector($document);
            $domDocumentFactory = $selector->select();            
            $document->setDomDocument($domDocumentFactory->getDomDocument());
            
        }

        return $document->getDomDocument();
    }
    
    public function getDomDocumentFactorySelector(Document $document)
    {
        return new DomDocumentFactorySelector($document);
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        $domXpath = $this->getDocument()->getDomXpath();
        if (!$domXpath) {
            $domDocument = $this->getDomDocument();
            $domXpath = $this->getDomXpathFactory()->fromDomDocument($domDocument);
            $this->getDocument()->setDomXpath($domXpath);
        }

        return $domXpath;
    }
}
