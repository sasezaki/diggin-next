<?php
namespace Diggin\DocumentResolver;

use Diggin\HttpCharset\HttpCharsetManagerAwareTrait;
use Diggin\HttpCharset\Filter as HttpCharsetFilter;
use Diggin\HttpCharset\CharsetEncoding;
use Diggin\HtmlFormatter\HtmlFormatterAwareTrait;

abstract class AbstractDocumentResolver implements DomDocumentProviderInterface
{    
    use HttpCharsetManagerAwareTrait;
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
            
            // remove namespaces.   
            //$formattedContent = FormatterUtil::removeNamespaces($formattedContent);
            
            $domFactory = $this->getDomDocumentFactory();
            $domDocument = $domFactory->getDomDocument($formattedContent);
            $domDocument->preAmpersandEscape = $pre_ampersand_escape;
            
            //$html5domfactory = $this->getDomDocumentFactoryPluginManager()->plugin('html5');
            //$html5domfactory->getDom
            
            
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
