<?php
namespace Diggin\DocumentResolver;

trait DomDocumentFactoryAwareTrait
{
    /**
     * @return DomDocumentFactory
     */
    public function getDomDocumentFactory()
    {
        return new DomDocumentFactory();
    }

} 