<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

class FactorySelector
{
    private $document;
    
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
    
    /**
     * detect & select factory
     * 
     * @todo detect from stream
     */ 
    public function select()
    {
        $document = $this->document;
        $content = $document->getContent();
        
        // breaking XML declaration to make syntax highlighting work
        if ('<' . '?xml' == substr(trim($content), 0, 5)) {
            if (preg_match('/<html[^>]*xmlns="([^"]+)"[^>]*>/i', $content, $matches)) {
                
                return new XHTMLStrategy($document, $matches[1]); 
            }
            
            //return $this->setDocumentXml($content, $encoding);
        }
        
        // html will be formatted as XHTML with HTMLFormatter in XHTMLStrategy
        
        return new XHTMLStrategy($document); 
        
        
        
//         // breaking XML declaration to make syntax highlighting work
//         if ('<' . '?xml' == substr(trim($document), 0, 5)) {
//             if (preg_match('/<html[^>]*xmlns="([^"]+)"[^>]*>/i', $document, $matches)) {
//                 $this->xpathNamespaces[] = $matches[1];
//                 return $this->setDocumentXhtml($document, $encoding);
//             }
//             return $this->setDocumentXml($document, $encoding);
//         }
//         if (strstr($document, 'DTD XHTML')) {
//             return $this->setDocumentXhtml($document, $encoding);
//         }
//         return $this->setDocumentHtml($document, $encoding);

        $document = $this->document;
        //$document->getStream()
        
        return new XHTMLStrategy($document);
    }
} 