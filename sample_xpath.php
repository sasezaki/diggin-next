<?php
use Diggin\DocumentResolver\UriDocumentResolver;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');


$documentInvoker = function ($uri) {
    $content = <<<'HTML'
<html><title>test title</title><body>test & body&lt;</body></html>    
HTML;

    $document = new \Diggin\DocumentResolver\Document();
    $document->setUri($uri);
    $document->setContent($content);
    $document->getHttpMessage()->setHeader('Content-Type', 'text/html; charset=Shift-JIS;');
        
    return $document;
};

// $domInvoker = $documentResolver->domDocument();
// $domInvoker();


$documentResolver = new UriDocumentResolver('http://musicrider.com/', $documentInvoker);

$domXpath = $documentResolver->getDomXpath();

/** @var DOMNodeList $nodeList */
$nodeList = $domXpath->evaluate('php:functionString("strtoupper", //title)');
var_dump($nodeList);
$nodeList = $domXpath->evaluate('//body');
var_dump($nodeList->item(0)->textContent);

// customize if you need
/** @var \Diggin\DocumentResolver\DomDocumentFactory\FormattingHtmlStrategy $strategy */
$strategy = $documentResolver->getStrategyPluginManager()->get('html');
$strategy->getHtmlFormatter(); // Diggin\HtmlFormatter\TidyHtmlFormatter
$strategy->getHttpCharsetManager()->getDetectorPluginManager()->get('html'); // Diggin\HttpCharset\Detector\HtmlDetector

// $documentResolver->getDomDocumentFactorySelector()->
// $documentResolver->getDomXpathFactory()->registerXpathPhpFunctions($xpathPhpFunctions)