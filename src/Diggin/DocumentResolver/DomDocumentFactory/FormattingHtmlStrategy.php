<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

use Diggin\HttpCharset\HttpCharsetManagerAwareTrait;
use Diggin\HttpCharset\Filter as HttpCharsetFilter;
use Diggin\HttpCharset\CharsetEncoding;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;

/**
 * This strategy use HtmlFormatter (relies on tidy extension)
 */
class FormattingHtmlStrategy implements AssembleDomDocumentInterface
{
    use HttpCharsetManagerAwareTrait;
    use HtmlFormatterAwareTrait;
    
    public function assembleDomDocument(Document $document)
    {
        $charsetManager = $this->getHttpCharsetManager();
        $matched = $charsetManager->matchUri($document->getUri());
        
        if ($matched) {
            $encoding = $matched->charsetEncoding;
            $content = $document->getContent();
        } else {
            // todo check if $document instanceof getHttpMessageAware...
            $httpMessage = $document->getHttpMessage();
            if ($httpMessage->hasHeader('Content-Type')) {
                $contentType = $httpMessage->getHeader('Content-Type');
            } else {
                $contentType = null;
            }
            $content = $document->getContent();
        
            $content = HttpCharsetFilter::removeBomAndNulls($content);
            $from_encoding = $charsetManager->detect($content, $contentType);
        
            // if $document instanceof detectedCharsetEncoding
            //$document->setDetectedEncoding($encoding);
        
            $content = CharsetEncoding::convert($content, 'UTF-8', $from_encoding);
        }
        
        
        
        $pre_ampersand_escape = true;
        
        $formatter = $this->getHtmlFormatter();
        $formatter->setConfig(['pre_ampersand_escape' => $pre_ampersand_escape]);
        $formattedContent = $formatter->format($content);
                
        $loadHTMLMethod = new LoadHTMLMethod();
        
        $domDocument = $loadHTMLMethod->getDomDocument($formattedContent, $from_encoding);
        $domDocument->preAmpersandEscape = $pre_ampersand_escape;
        
        $document->setDomDocumentErrors($loadHTMLMethod->getDocumentErrors());
        
        return $domDocument;
    }
}