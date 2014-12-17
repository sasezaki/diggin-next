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
}