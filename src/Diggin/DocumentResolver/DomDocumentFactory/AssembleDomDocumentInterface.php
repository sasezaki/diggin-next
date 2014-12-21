<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\Document;

interface AssembleDomDocumentInterface
{
    public function assemble(Document $document);    
}