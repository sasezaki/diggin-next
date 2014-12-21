<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

interface AssembleDomDocumentInterface
{
    public function assembleDomDocument(Document $document);    
}