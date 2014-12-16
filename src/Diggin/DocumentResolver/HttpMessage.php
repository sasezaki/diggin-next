<?php
namespace Diggin\DocumentResolver;

use DOMDocument;
use DOMXPath;

/**
 * @todo Consider psr-7 Psr\Http\Message\MessageInterface
 */
class HttpMessage
{
    protected $headers = [];
    
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }
    
    public function getHeader($header)
    {
        return $this->headers[$header];
    }   
}