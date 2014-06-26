<?php
/**
 * Created by PhpStorm.
 * User: kazusuke
 * Date: 14/06/27
 * Time: 1:17
 */

namespace Diggin\DocumentResolver;


class DocumentResolverFactory
{
    public static function fromUri($uri, $originUri = false)
    {
        $resolver = new StandardDocumentResolver();
        $resolver->setUri($uri);
        return $resolver;
    }

    public static function fromDocument(Document $document)
    {

    }

} 