<?php

declare(strict_types=1);

namespace Ruga\Asset\Helper;

use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\InlineScript;

class HeadLinkAssets extends \Laminas\View\Helper\AbstractHelper
{
    /** @var array */
    private $config;
    
    /** @var InlineScript */
    private $inlineScript;
    
    /** @var HeadLink */
    private $headLink;
    
    
    
    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($inlineScript, $headLink, $config)
    {
        $this->inlineScript = $inlineScript;
        $this->headLink = $headLink;
        $this->config = $config;
    }
    
    
    
    /**
     * The __invoke method is called when a script tries to call an object as a function.
     *
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     */
    public function __invoke()
    {
        $aLoadedAssets = [];
        
        $assets=array_reverse($this->config);
        
        foreach ($assets as $assetname => $asset) {
            $aLoadedAssets[] = $assetname;
            if (isset($asset['scripts']) && is_array($asset['scripts'])) {
                foreach (array_reverse($asset['scripts']) as $item) {
                    ($this->inlineScript)()->prependFile(
                        "ruga/vendorasset/{$assetname}/{$item}"
                    );
                }
            }
            
            if (isset($asset['stylesheets']) && is_array($asset['stylesheets'])) {
                foreach (array_reverse($asset['stylesheets']) as $item) {
                    ($this->headLink)()->prependStylesheet(
                        "ruga/vendorasset/{$assetname}/{$item}"
                    );
                }
            }
        }
        
        return '<!-- ' . var_export($aLoadedAssets, true) . ' -->';
    }
    
}