<?php
use Diggin\DocumentResolver\DocumentResolverFactory;
use Diggin\DocumentResolver\StandardDocumentResolver;
use Diggin\DocumentResolver\GetDomDocumentResolverInterface;

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once __DIR__.'/vendor/autoload.php';
$autoload->add('Diggin\\', __DIR__.'/src');

class Scraper
{
    public function scrape(GetDomDocumentResolverInterface $documentResolver)
    {
        $documentResolver->getDomDocument();
    }
}

$documentResolver = new StandardDocumentResolver;
$documentResolver->setUri('http://musicrider.com/');
//$documentResolver->setHtmlFormatter(new \Diggin\HtmlFormatter\HtmlFormatter());

$domXpath = $documentResolver->getDomXpath();

/** @var DOMNodeList $nodeList */
$nodeList = $domXpath->evaluate('php:functionString("strtoupper", //title)');
var_dump($nodeList);
$nodeList = $domXpath->evaluate('//body');
var_dump($nodeList->item(0)->textContent);

die;
$uri = '';


class Sample
{
    /**
     * @Diggin\Scraper\xpath ''
     * @Diggin\Scraper\type @href
     *
     * @var string
     */
    public $a;
}

//$scraper = new Scraper();
//$scraper->scrape(DocumentResolverFactory::fromUri($uri));