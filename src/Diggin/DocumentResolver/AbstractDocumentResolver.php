<?php
namespace Diggin\DocumentResolver;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\Event;
use Diggin\HttpCharset\HttpCharsetManagerAwareTrait;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{
    use EventManagerAwareTrait;
    
    use HttpCharsetManagerAwareTrait;
    use HtmlFormatterAwareTrait;

    use DomDocumentFactoryAwareTrait;
    use DomXpathFactoryAwareTrait;

    /**
     * @return Document
     */
    abstract protected function getDocument();
    
    /**
     * A proxy to Document's getContent
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
            
            $charsetManager = $this->getHttpCharsetManager();
            $matched = $charsetManager->matchUri($document->getUri());
            
            if ($matched) {
                $encoding = $matched->charsetEncoding;
                $content = $document->getContent();
            } else {
                // todo check if $document instanceof getHttpMessageAware...           
                $event = new Event('detect.pre', $this, [$document]);
                
                $contentType = $document->getHttpMessage()->getHeader('Content-Type');
                $content = $document->getContent();
                $this->getEventManager()->trigger($event);
                
                $encoding = $charsetManager->detect($content, $contentType);
                
                // if $document instanceof detectedCharsetEncoding
                //$document->setDetectedEncoding($encoding);

                $content = $charsetManager->convert($content, $encoding);
            }
            
            $formattedContent = $this->getHtmlFormatter()->format($content);
            $domFactory = $this->getDomDocumentFactory();
            $domDocument = $domFactory->getDomDocument($formattedContent);
            $document->setDomDocument($domDocument);
        }

        return $document->getDomDocument();
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXpath()
    {
        $domDocument = $this->getDomDocument();
        $domXpath = $this->getDocument()->getDomXpath();
        if (!$domXpath) {
            $domXpath = $this->getDomXpathFactory()->fromDomDocument($domDocument);
            $this->getDocument()->setDomXpath($domXpath);
        }

        return $domXpath;
    }
}
