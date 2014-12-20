<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

class FactorySelector
{   
    const SELECT_XHTML = 'xhtml';
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
//                 return new XHTMLStrategy($document);
                return self::SELECT_XHTML;
            }
            
            //return $this->setDocumentXml($content, $encoding);
        }
        
        // html will be formatted as XHTML with HTMLFormatter in XHTMLStrategy
        
        return self::SELECT_XHTML;
//         return new XHTMLStrategy($document); 
    }
} 