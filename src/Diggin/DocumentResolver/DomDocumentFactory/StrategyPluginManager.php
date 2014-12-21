<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Zend\ServiceManager\AbstractPluginManager;
use Diggin\DocumentResolver\Exception;

class StrategyPluginManager extends AbstractPluginManager
{
    /**
     * Default set of strategies
     *
     * @var array
     */
    protected $invokableClasses = [
        'html' =>  'Diggin\DocumentResolver\DomDocumentFactory\FormattingHtmlStrategy',
        'xhtml' => 'Diggin\DocumentResolver\DomDocumentFactory\FormattingHtmlStrategy',
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
        if ($plugin instanceof AssembleDomDocumentInterface) {
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