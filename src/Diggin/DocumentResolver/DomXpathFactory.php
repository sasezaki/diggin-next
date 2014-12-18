<?php
namespace Diggin\DocumentResolver;

use DOMXPath;

class DomXpathFactory
{
    private $xpathNamespaces = [];
    private $xpathPhpFunctions = true;

    public function fromDomDocument(\DOMDocument $domDocument)
    {
        $xpath      = new DOMXPath($domDocument);
        foreach ($this->xpathNamespaces as $prefix => $namespaceUri) {
            $xpath->registerNamespace($prefix, $namespaceUri);
        }
        if ($this->xpathPhpFunctions) {
            $xpath->registerNamespace("php", "http://php.net/xpath");
            ($this->xpathPhpFunctions === true) ?
                $xpath->registerPHPFunctions()
                : $xpath->registerPHPFunctions($this->xpathPhpFunctions);
        }

        return $xpath;
    }
} 