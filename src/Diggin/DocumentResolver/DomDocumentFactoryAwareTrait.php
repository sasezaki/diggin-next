<?php
/**
 * Created by PhpStorm.
 * User: kazusuke
 * Date: 14/06/27
 * Time: 0:11
 */

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