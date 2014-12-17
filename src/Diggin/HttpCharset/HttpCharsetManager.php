<?php
namespace Diggin\HttpCharset;

use Diggin\HttpCharset\Detector\DetectorInterface;
class HttpCharsetManager
{
    use DetectorPluginManagerAwareTrait;
        
    public function matchUri($uri)
    {
        return false;
    }

    /**
     * @todo if contentType is null
     * 
     * @param string $body
     * @param string $contentType
     * @throws Exception\DetectException
     */
    public function detect($body, $contentType = null, $detectorName = 'html')
    {
        /**
        if (preg_match('#^text/html#i', $contentType)) {
            $detectorName = 'html';
        } else {
            throw new Exception\DetectException();
        }*/
        
        /** @var \Diggin\HttpCharset\Detector\DetectorInterface $detector */
        $detector = $this->getDetectorPluginManager()->get($detectorName);
        return $detector->detect($body, $contentType);
    }
}