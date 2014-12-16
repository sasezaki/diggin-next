<?php
namespace Diggin\DocumentResolver;

trait DomXpathFactoryAwareTrait
{
    public function getDomXpathFactory()
    {
        return new DomXpathFactory();
    }
}