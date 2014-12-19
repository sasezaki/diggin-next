<?php
namespace Diggin\DocumentResolver;

use DOMXPath;

class DomXpathFactory
{
    private $xpathPhpFunctions = true;
    
    public function registerXpathPhpFunctions($xpathPhpFunctions)
    {
        $this->xpathPhpFunctions = $xpathPhpFunctions;
    }
    
    public function fromDomDocument(\DOMDocument $domDocument, array $xpathNamespaces = [])
    {
        $xpath = new DOMXPath($domDocument);
        foreach ($xpathNamespaces as $prefix => $namespaceUri) {
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