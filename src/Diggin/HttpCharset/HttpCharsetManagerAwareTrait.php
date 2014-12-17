<?php
namespace Diggin\HttpCharset;

trait HttpCharsetManagerAwareTrait
{
    protected $httpCharsetManager;
    
    public function getHttpCharsetManager()
    {
        if (!$this->httpCharsetManager instanceof HttpCharsetManager) {
            $this->httpCharsetManager = new HttpCharsetManager();
        }
        return $this->httpCharsetManager;
    }
}