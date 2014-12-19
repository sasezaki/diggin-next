<?php
namespace Diggin\DocumentResolver;

class DocTypeDetector
{
    const DETECTED_HTML5 = 1;
    const DETECTED_NOT_HTML5 = 0;
    
    public function fromStream($stream)
    {
        
    }
    
    public function fromString($html)
    {
        return self::DETECTED_NOT_HTML5;
    }
}