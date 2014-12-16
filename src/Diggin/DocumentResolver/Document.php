<?php
namespace Diggin\DocumentResolver;

use DOMDocument;
use DOMXPath;

/**
 * @todo Consider psr-7 Psr\Http\Message\MessageInterface
 */
class Document
{
    private $uri;

    private $content;
    private $domDocument;
    private $domXpath;
    private $httpMessage;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    /**
     * @return HttpMessage
     */
    public function getHttpMessage()
    {
        if (!$this->httpMessage instanceof HttpMessage) {
            $this->httpMessage = new HttpMessage();
        }
        
        return $this->httpMessage;
    }
    
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