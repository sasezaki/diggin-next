<?php
namespace Diggin\HttpCharset;

class HttpCharsetManager
{
    use DetectorPluginManagerAwareTrait;
        
    public function matchUri($uri)
    {
        return false;
    }

    public function detect($body, $contentType)
    {
        if (preg_match('#^text/html#i', $contentType)) {
            $detector = 'html';
        } else {
            throw new Exception\DetectException();
        }
        
        $detector = $this->getDetectorPluginManager()->get($detector);
        return $detector->detect($body, $contentType);
    }
    
    public function convert($body, $encoding_from)
    {
        //$body = $this->_initBody($body);
        
        // if not available for mbstring, use iconv.
        if (!in_array($encoding_from, mb_list_encodings())) {
            $body = @iconv($encoding_from, 'UTF-8', $body);
            return $body;
        }
        
        $body = mb_convert_encoding($body, 'UTF-8', $encoding_from);
        return $body;
    }
}