<?php
namespace Diggin\HttpCharset;

use Zend\ServiceManager\AbstractPluginManager;
use Diggin\HttpCharset\Detector\DetectorInterface;
use Diggin\HttpCharset\Exception;

class DetectorPluginManager extends AbstractPluginManager
{
    /**
     * Default set of detectors
     *
     * @var array
     */
    protected $invokableClasses = [
        'html' => 'Diggin\HttpCharset\Detector\HtmlDetector',
    ];
    
    /**
     * Validate the plugin
     *
     * Checks that the adapter loaded is an instance of StorageInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof DetectorInterface) {
            // we're okay
            return;
        }
        
        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Detector\DetectorInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}