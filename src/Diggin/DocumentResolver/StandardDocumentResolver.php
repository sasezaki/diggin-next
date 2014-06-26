<?php
namespace Diggin\DocumentResolver;

use Diggin\HttpCharset\HttpCharsetFrontAwareTrait;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;

class StandardDocumentResolver implements GetDomDocumentInterface
{
    use HttpCharsetFrontAwareTrait;
    use HtmlFormatterAwareTrait;

    use DomDocumentFactoryAwareTrait;
    use DomXpathFactoryAwareTrait;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var Document
     */
    private $document;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getContent()
    {
        $this->getDocument()->getContent();
    }

    public function getDomDocument()
    {
        $document = $this->getDocument();
        if (!$document->getDomDocument()) {
            $content = $document->getContent();

            $charsetFront = $this->getHttpCharsetFront();
            $encoding = $charsetFront->detect();
            $content = $charsetFront->convert($document->getContent());
            $formattedContent = $this->getHtmlFormatter()->format($content);
            $domFactory = $this->getDomDocumentFactory();
            $domFactory->setEncoding('UTF-8');
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

    /**
     * @return Document
     */
    protected function getDocument()
    {
        if (!$this->document instanceof Document) {

            $content = file_get_contents($this->uri);
            $document = new Document();

            $document->setContent($content);
            $this->document = $document;
        }
        return $this->document;
    }
}
