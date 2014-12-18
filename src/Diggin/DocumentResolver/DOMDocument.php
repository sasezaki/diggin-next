<?php
namespace Diggin\DocumentResolver;

class DOMDocument extends \DOMDocument
{
    public $pre_ampersand_escape = false;
    
    public function saveHTML(\DOMNode $node = null)
    {
        
        if ($this->preAmpersandEscape === true) {
            return htmlspecialchars_decode(parent::saveHTML($node), ENT_NOQUOTES);            
        }
        
        return parent::saveHTML($node);
    }
}