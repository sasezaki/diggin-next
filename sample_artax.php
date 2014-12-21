<?php
use Diggin\DocumentResolver\UriDocumentResolver;
use Diggin\DocumentResolver\DocumentInvoker\ArtaxResponseDocumentInvoker;
use Diggin\DocumentResolver\DocumentInvoker\ArtaxClientDocumentInvoker;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');


$documentInvoker = new ArtaxClientDocumentInvoker();
$request = $documentInvoker->getDefaultRequest();
$request->appendHeader('User-Agent', "diggin");

// one wait each uri with https 
$documentResolver = new UriDocumentResolver('https://github.com/composer/composer/commit/ac676f47f7bbc619678a29deae097b6b0710b799', $documentInvoker);
$domXpath = $documentResolver->getDomXpath();
var_dump($domXpath->evaluate('//body'));

//requestMulti

/** */
$uris = [
    'delay1'    => 'http://httpbin.org/delay/1',
    'delay3'    => 'http://httpbin.org/delay/3',
    'delay5'    => 'http://httpbin.org/delay/5',
];

$client= new Amp\Artax\Client;

$promises = [];
foreach ($uris as $k => $uri) {
    $promises[] = $client->request($uri)->when(function($error, $result) use ($uri) {
        echo time(), PHP_EOL;
        
        $documentResolver = new UriDocumentResolver($uri, new ArtaxResponseDocumentInvoker($result));
        $domXpath = $documentResolver->getDomXpath();
        var_dump($domXpath->evaluate('//body')->item(0)->nodeValue);
    });
}

$comboPromise = Amp\some($promises);
list($errors, $responses) = Amp\wait($comboPromise);



