<?php
namespace Diggin\DocumentResolver;

use DOMDocument;
use DOMXPath;

class Document
{
    private $uri;
    private $originUri;

    private $content;
    private $domDocument;
    private $domXpath;

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setDomDocument(DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
    }

    public function setDomXpath(DOMXPath $domXpath)
    {
        $this->domXpath = $domXpath;
    }

    /**
     * @return \DomDocument
     */
    public function getDomDocument()
    {
        return $this->domDocument;
    }

    public function getDomXpath()
    {
        return $this->domXpath;
    }
}