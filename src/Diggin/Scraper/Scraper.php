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
        // $documentResolver->getDomDocument();
        
        $domXpath = $documentResolver->getDomXpath();
        $xpath = $this->xpathTransfomer->xpathOrCss2Xpath($process->getExpression());
        $nodeList = $domXpath->evaluate($xpath);
        
        var_dump(get_class($nodeList->item(0)->ownerDocument));
        
        $domElement = $nodeList->item(0);
        
        return $domElement->ownerDocument->saveHTML($domElement);
//         return $domElement->textContent;
    }
}

// class DomCrawlerBridge
// {
//     public function scrape()
//     {
//         $domDocument = $documentResolver->getDomDocument();
        
//  //       new Symfony\Component\DomCrawler...
//     }
// }
