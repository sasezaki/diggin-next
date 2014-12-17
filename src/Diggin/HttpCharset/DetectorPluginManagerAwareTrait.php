<?php
namespace Diggin\HttpCharset;

trait DetectorPluginManagerAwareTrait
{
    protected $detectorPluginManager;
    
    public function setDetectorPluginManager($detectorPluginManager)
    {
        $this->detectorPluginManager = $detectorPluginManager;
    }
    
    public function getDetectorPluginManager()
    {
        if (!$this->detectorPluginManager instanceof DetectorPluginManager) {
            $this->detectorPluginManager = new DetectorPluginManager();
        }
        
        return $this->detectorPluginManager;
    }
}