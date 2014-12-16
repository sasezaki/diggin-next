<?php
namespace Diggin\DocumentResolver;

use Diggin\HttpCharset\HttpCharsetFrontAwareTrait;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{
    use HttpCharsetFrontAwareTrait;
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
            $content = $document->getContent();

            $charsetFront = $this->getHttpCharsetFront();
            $encoding = $charsetFront->detect($content);
            $content = $charsetFront->convert($document->getContent());
            $formattedContent = $this->getHtmlFormatter()->format($content);
            $domFactory = $this->getDomDocumentFactory();
            $domFactory->setEncoding($encoding);
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
