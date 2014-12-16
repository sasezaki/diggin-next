<?php
namespace Diggin\DocumentResolver;

trait DomXpathFactoryAwareTrait
{
    protected $domXpathFactory;
    
    public function getDomXpathFactory()
    {
        if (!$this->domXpathFactory instanceof DomXpathFactory) {
            $this->domXpathFactory = new DomXpathFactory();
        }
        
        return $this->domXpathFactory;
    }
}