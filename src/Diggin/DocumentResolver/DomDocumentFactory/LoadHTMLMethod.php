<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\DOMDocument;
use Diggin\DocumentResolver\Exception;

/**
 * This code fragment is originally from the Zend\Dom.
 * 
 * @license New BSD, code from Zend Framework
 */
class LoadHTMLMethod
{
    /**
     * DOMDocument errors, if any
     * @var false|array
     */
    protected $documentErrors = false;

    /**
     * Get any DOMDocument errors found
     *
     * @return false|array
     */
    public function getDocumentErrors()
    {
        return $this->documentErrors;
    }

    public function getDomDocument($document, $encoding)
    {
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);
        if (null === $encoding) {
            $domDoc = new DOMDocument('1.0');
        } else {
            $domDoc = new DOMDocument('1.0', $encoding);
        }
        
        $success = $domDoc->loadHTML($document);
        
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $this->documentErrors = $errors;
            libxml_clear_errors();
        }
        libxml_disable_entity_loader(false);
        libxml_use_internal_errors(false);

        if (!$success) {
            throw new Exception\RuntimeException(sprintf('Error parsing document'));
        }

        return $domDoc;
    }
}