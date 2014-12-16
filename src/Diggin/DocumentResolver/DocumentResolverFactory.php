<?php
namespace Diggin\DocumentResolver;

class DocumentResolverFactory
{
    public static function fromUri($uri)
    {
        $resolver = new UriDocumentResolver($uri);
        $resolver->setUri($uri);
        return $resolver;
    }

    public static function fromDocument(Document $document)
    {

    }

} 