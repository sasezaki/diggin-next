<?php
use Diggin\DocumentResolver\UriDocumentResolver;
use Diggin\DocumentResolver\DocumentInvoker\ArtaxDocumentInvoker;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');


$documentInvoker = new ArtaxDocumentInvoker();
$request = $documentInvoker->getDefaultRequest();
$request->appendHeader('User-Agent', "diggin");

$documentResolver = new UriDocumentResolver('http://httpbin.org/user-agent', $documentInvoker);
//$documentResolver = new UriDocumentResolver('https://github.com/composer/composer/commit/ac676f47f7bbc619678a29deae097b6b0710b799', $documentInvoker);

$domXpath = $documentResolver->getDomXpath();

/** @var DOMNodeList $nodeList */
$nodeList = $domXpath->evaluate('php:functionString("strtoupper", //title)');
var_dump($nodeList);
$nodeList = $domXpath->evaluate('//body');
var_dump($nodeList->item(0)->textContent);
