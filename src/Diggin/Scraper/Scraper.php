<?php
namespace Diggin\Scraper;

use Diggin\DocumentResolver\DomDocumentProviderInterface;

class Scraper
{
	protected $xpathTransfomer;
	
	public function __construct($xpathTransfomer = null)
	{
	    $this->xpathTransfomer = ($xpathTransfomer) ?: new XpathTransformer();
	}
	
    public function scrape(DomDocumentProviderInterface $documentResolver, Process $process)
    {
        //$documentResolver->getHtmlFormatter()->setConfig(['pre_ampersand_escape' => true]);
        // $documentResolver->getDomDocument();
        
        $domXpath = $documentResolver->getDomXpath();
        $xpath = $this->xpathTransfomer->xpathOrCss2Xpath($process->getExpression());
        $nodeList = $domXpath->evaluate($xpath);
        
        return $nodeList->item(0)->textContent;
    }
}
