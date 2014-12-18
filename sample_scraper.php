<?php
use Diggin\Scraper\Scraper;
use Diggin\DocumentResolver\UriDocumentResolver;
use Diggin\Scraper\Process;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');


$documentInvoker = function ($uri) {
    $content = <<<'HTML'
<html><title>test title</title><body>aa<span class="main">test<span>EM</span> &amp; -> body</span>bbbb</body></html>    
HTML;

    $document = new \Diggin\DocumentResolver\Document();
    $document->setUri($uri);
    $document->setContent($content);
    $document->getHttpMessage()->setHeader('Content-Type', 'text/html; charset=Shift-JIS;');
        
    return $document;
};

$documentResolver = new UriDocumentResolver('http://musicrider.com/', $documentInvoker);

$scraper = new Scraper();

$process = new Process();
$process->setType('dom');
$process->setExpression('//span[@class="main"]');

// function (DOMDocument $domDocument) {}    // class DirectProcess
// ['body' => 'type', $filter1, $filter2], $arrayFlag;
// ['body' => function() {}], $arrayFlag;


var_dump($scraper->scrape($documentResolver, $process));

