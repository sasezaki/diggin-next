<?php
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