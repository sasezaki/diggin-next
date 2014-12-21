<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

class FactorySelector
{
    const SELECT_HTML = 'html';
    const SELECT_XHTML = 'xhtml';
    const SELECT_HTML5 = 'html5';
    const SELECT_XML = 'xml';
    
    /**
     * detect & select factory
     * 
     * @todo detect from stream
     * @todo HTML5
     */ 
    public function select(Document $document)
    {
        $content = $document->getContent();
        
        // breaking XML declaration to make syntax highlighting work
        if ('<' . '?xml' == substr(trim($content), 0, 5)) {
            if (preg_match('/<html[^>]*xmlns="([^"]+)"[^>]*>/i', $content, $matches)) {
                $document->setDomXpathNamespaces($matches[1]);
                return self::SELECT_XHTML;
            }
            
            return self::SELECT_XML;
        }
        
        return self::SELECT_HTML;
    }
} 