<?php
use Diggin\DocumentResolver\ArtaxResponseDocumentResolver;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');

$uris = [
    'delay1'    => 'http://httpbin.org/delay/1',
    'delay5'    => 'http://httpbin.org/delay/5',
    'delay3'    => 'http://httpbin.org/delay/3',
];

$client= new Amp\Artax\Client;
$documentResolver = new ArtaxResponseDocumentResolver;

$promises = [];
foreach ($uris as $k => $uri) {
    $promises[] = $client->request($uri)->when(function($error, $response) use ($documentResolver) {
        echo time(), PHP_EOL;
        
        $domXpath = $documentResolver->getDomXpath($response);
        var_dump($domXpath->evaluate('//body')->item(0)->nodeValue);
    });
}

$comboPromise = Amp\some($promises);
list($errors, $responses) = Amp\wait($comboPromise);