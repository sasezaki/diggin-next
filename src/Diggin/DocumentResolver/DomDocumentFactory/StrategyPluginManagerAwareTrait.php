<?php
namespace Diggin\DocumentResolver\DomDocumentFactory;

use Diggin\DocumentResolver\DomDocumentFactory\StrategyPluginManager;

trait StrategyPluginManagerAwareTrait
{
    protected $strategyPluginManager;
    
    public function setStrategyPluginManager($strategyPluginManager)
    {
        $this->strategyPluginManager = $strategyPluginManager;
    }
    
    public function getStrategyPluginManager()
    {
        if (!$this->strategyPluginManager instanceof StrategyPluginManager) {
            $this->strategyPluginManager = new StrategyPluginManager();
        }
        
        return $this->strategyPluginManager;
    }
}