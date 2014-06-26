<?php
namespace Diggin\DocumentResolver;

use DOMDocument;

class DomDocumentFactory
{
    /**#@+
     * Document types
     */
    const DOC_XML   = 'docXml';
    const DOC_HTML  = 'docHtml';
    const DOC_XHTML = 'docXhtml';
    /**#@-*/

    /**
     * @var string
     */
    protected $document;

    /**
     * DOMDocument errors, if any
     * @var false|array
     */
    protected $documentErrors = false;

    /**
     * Document type
     * @var string
     */
    protected $docType;

    /**
     * Document encoding
     * @var null|string
     */
    protected $encoding;

    /**
     * XPath namespaces
     * @var array
     */
    protected $xpathNamespaces = array();

    /**
     * XPath PHP Functions
     * @var mixed
     */
    protected $xpathPhpFunctions;

    /**
     * Constructor
     *
     * @param null|string $document
     * @param null|string $encoding
     */
    public function __construct($document = null, $encoding = null)
    {
        $this->setEncoding($encoding);
        $this->setDocument($document);
    }

    /**
     * Set document encoding
     *
     * @param  string $encoding
     * @return Query
     */
    public function setEncoding($encoding)
    {
        $this->encoding = (null === $encoding) ? null : (string) $encoding;
        return $this;
    }

    /**
     * Get document encoding
     *
     * @return null|string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set document to query
     *
     * @param  string $document
     * @param  null|string $encoding Document encoding
     * @return Query
     */
    public function setDocument($document, $encoding = null)
    {
        if (0 === strlen($document)) {
            return $this;
        }
        // breaking XML declaration to make syntax highlighting work
        if ('<' . '?xml' == substr(trim($document), 0, 5)) {
            if (preg_match('/<html[^>]*xmlns="([^"]+)"[^>]*>/i', $document, $matches)) {
                $this->xpathNamespaces[] = $matches[1];
                return $this->setDocumentXhtml($document, $encoding);
            }
            return $this->setDocumentXml($document, $encoding);
        }
        if (strstr($document, 'DTD XHTML')) {
            return $this->setDocumentXhtml($document, $encoding);
        }
        return $this->setDocumentHtml($document, $encoding);
    }

    /**
     * Register HTML document
     *
     * @param  string $document
     * @param  null|string $encoding Document encoding
     * @return Query
     */
    public function setDocumentHtml($document, $encoding = null)
    {
        $this->document = (string) $document;
        $this->docType  = self::DOC_HTML;
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }
        return $this;
    }

    /**
     * Register XHTML document
     *
     * @param  string $document
     * @param  null|string $encoding Document encoding
     * @return Query
     */
    public function setDocumentXhtml($document, $encoding = null)
    {
        $this->document = (string) $document;
        $this->docType  = self::DOC_XHTML;
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }
        return $this;
    }

    /**
     * Register XML document
     *
     * @param  string $document
     * @param  null|string $encoding Document encoding
     * @return Query
     */
    public function setDocumentXml($document, $encoding = null)
    {
        $this->document = (string) $document;
        $this->docType  = self::DOC_XML;
        if (null !== $encoding) {
            $this->setEncoding($encoding);
        }
        return $this;
    }

    /**
     * Retrieve current document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Get document type
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->docType;
    }

    /**
     * Get any DOMDocument errors found
     *
     * @return false|array
     */
    public function getDocumentErrors()
    {
        return $this->documentErrors;
    }


    public function getDomDocument($document)
    {
        $encoding = $this->getEncoding();
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);
        if (null === $encoding) {
            $domDoc = new DOMDocument('1.0');
        } else {
            $domDoc = new DOMDocument('1.0', $encoding);
        }
        $type   = $this->getDocumentType();
        switch ($type) {
            case self::DOC_XML:
                $success = $domDoc->loadXML($document);
                foreach ($domDoc->childNodes as $child) {
                    if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                        throw new Exception\RuntimeException(
                            'Invalid XML: Detected use of illegal DOCTYPE'
                        );
                    }
                }
                break;
            case self::DOC_HTML:
            case self::DOC_XHTML:
            default:
                $success = $domDoc->loadHTML($document);
                break;
        }
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $this->documentErrors = $errors;
            libxml_clear_errors();
        }
        libxml_disable_entity_loader(false);
        libxml_use_internal_errors(false);

        if (!$success) {
            throw new Exception\RuntimeException(sprintf('Error parsing document (type == %s)', $type));
        }

        return $domDoc;
    }
}