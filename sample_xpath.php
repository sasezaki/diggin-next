<?php
use Diggin\DocumentResolver\UriDocumentResolver;
use Diggin\DocumentResolver\DomDocumentProviderInterface;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');


$stringDocumentResolver = function ($uri) {
    $content = <<<'HTML'
<html><title>test title</title><body>test & body&lt;</body></html>    
HTML;

    $document = new \Diggin\DocumentResolver\Document();
    $document->setUri($uri);
    $document->setContent($content);
    $document->getHttpMessage()->setHeader('Content-Type', 'text/html; charset=Shift-JIS;');
        
    return $document;
};

$documentResolver = new UriDocumentResolver('http://musicrider.com/', $stringDocumentResolver);

$domXpath = $documentResolver->getDomXpath();

/** @var DOMNodeList $nodeList */
$nodeList = $domXpath->evaluate('php:functionString("strtoupper", //title)');
var_dump($nodeList);
$nodeList = $domXpath->evaluate('//body');
var_dump($nodeList->item(0)->textContent);