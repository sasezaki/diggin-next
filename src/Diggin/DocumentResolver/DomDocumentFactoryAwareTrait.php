<?php
namespace Diggin\DocumentResolver;

trait DomDocumentFactoryAwareTrait
{
    protected $domDocumentFactory;
    
    /**
     * @return DomDocumentFactory
     */
    public function getDomDocumentFactory()
    {
        if (!$this->domDocumentFactory instanceof DomDocumentFactory) {
            $this->domDocumentFactory = new DomDocumentFactory();
        }
        
        return $this->domDocumentFactory;
    }

} 