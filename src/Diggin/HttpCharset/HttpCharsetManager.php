<?php
namespace Diggin\HttpCharset;

class HttpCharsetManager
{
    public function matchUri($uri)
    {
        return false;
    }
    
    public function detect($body, $contentType)
    {
        return 'UTF-8';
    }
    
    final public function convert($body, $encoding_from)
    {
        //$body = $this->_initBody($body);
        
        // if not avilable for mbstring, using iconv
        if (!in_array($encoding_from, mb_list_encodings())) {
            $body = @iconv($encoding_from, 'UTF-8', $body);
            return $body;
        }
        
        $body = mb_convert_encoding($body, 'UTF-8', $encoding_from);
        return $body;
    }

}