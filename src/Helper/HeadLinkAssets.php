<?php

declare(strict_types=1);

namespace Ruga\Asset\Helper;

use Laminas\Json\Decoder;
use Laminas\Json\Json;
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
    
    
    
    private function checkForMin(string $file, string $installPath): string
    {
        $minFile=str_replace(['.js', '.css', '.map'], ['.min.js', '.min.css', '.min.map'], $file);
        if(file_exists("{$installPath}/{$minFile}")) return $minFile;
        if(file_exists("{$installPath}/{$file}")) return $file;
        return '';
    }
    
    
    
    /**
     * The __invoke method is called when a script tries to call an object as a function.
     *
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     */
    public function __invoke()
    {
        $aPackageList = [];
        
        // Add the rugalib assets
        $assets = array_reverse($this->config);
        foreach ($assets as $assetname => $asset) {
            if(\Composer\InstalledVersions::isInstalled($assetname)) {
                $aPackageList[$assetname] = [];
            }
        }
        
        // Add all the composer packages
        foreach (\Composer\InstalledVersions::getInstalledPackages() as $assetname) {
            $aPackageList[$assetname] = [];
        }
        
        $aPackageLoadList=[];
        foreach ($aPackageList as $packageName => $packageConf) {
            if(!preg_match('#(rugalib/ruga-asset-|rugalib/ruga-layout-|rugalib/asset-|components/|npm-asset/|bower-asset/)#s', $packageName)) continue;
            $installPath = \Composer\InstalledVersions::getInstallPath($packageName);
            $packageConf=['installPath' => $installPath];
            
            if(array_key_exists($packageName, $this->config)) {
                $config = $this->config[$packageName];
                
                foreach(($config['require'] ?? []) as $name => $version) {
                    if(array_key_exists($name, $aPackageList)) {
                        $packageConf['require'][]=$name;
                        if(!array_key_exists($name, $aPackageLoadList)) {
                            $aPackageLoadList[$name] = [];
                        }
                    }
                }
    
                $packageConf['ruga-asset'] = $config;
            }
            
            if(file_exists($composerFile="{$installPath}/composer.json")) {
                try {
                    $composerJson = Decoder::decode(file_get_contents($composerFile), Json::TYPE_ARRAY);
                } catch (\ErrorException $e) {
                    $composerJson = [];
                }
                
                foreach(($composerJson['require'] ?? []) as $name => $version) {
                    if(array_key_exists($name, $aPackageList)) {
                        $packageConf['require'][]=$name;
                        if(!array_key_exists($name, $aPackageLoadList)) {
                            $aPackageLoadList[$name] = [];
                        }
                    }
                }
                
                $packageConf['composer.json'] = $composerJson;
            }
            
            if(file_exists($componentFile="{$installPath}/component.json")) {
                try {
                    $componentJson = Decoder::decode(file_get_contents($componentFile), Json::TYPE_ARRAY);
                } catch (\ErrorException $e) {
                    $componentJson = [];
                }
    
                foreach(($componentJson['dependencies'] ?? []) as $name => $version) {
                    if(array_key_exists($name, $aPackageList)) {
                        $packageConf['require'][]=$name;
                        if(!array_key_exists($name, $aPackageLoadList)) {
                            $aPackageLoadList[$name] = [];
                        }
                    }
                }
    
                $packageConf['component.json'] = $componentJson;
            }
            
            if(file_exists($packageFile="{$installPath}/package.json")) {
                try {
                    $packageJson = Decoder::decode(file_get_contents($packageFile), Json::TYPE_ARRAY);
                } catch (\ErrorException $e) {
                    $packageJson = [];
                }
    
                $packageConf['package.json'] = $packageJson;
            }
            
            if(file_exists($bowerFile="{$installPath}/bower.json")) {
                try {
                    $bowerJson = Decoder::decode(file_get_contents($bowerFile), Json::TYPE_ARRAY);
                } catch (\ErrorException $e) {
                    $bowerJson = [];
                }
    
                $packageConf['bower.json'] = $bowerJson;
            }
    
    
//            if(!in_array($packageName, $aPackageLoadList) && !empty($packageConf)) {
                $aPackageLoadList[$packageName] = $packageConf;
//            }
    
            $aPackageList[$packageName] = $packageConf;
        }
        
    
        
        
        
        
        
        
        foreach (array_reverse($aPackageLoadList) as $packageName => $packageConf) {
            $scripts=[];
            $stylesheets=[];
            
            if(array_key_exists('ruga-asset', $packageConf)) {
                $scripts=$packageConf['ruga-asset']['scripts'];
                $stylesheets=$packageConf['ruga-asset']['stylesheets'];
            }
            
            elseif(array_key_exists('composer.json', $packageConf)) {
                foreach ((($packageConf['composer.json']['extra'] ?? [])['component'] ?? [])['scripts'] ?? [] as $item ) {
                    $item=$this->checkForMin($item, $packageConf['installPath']);
                    if(!empty($item) && !in_array($item, $scripts)) $scripts[]=$item;
                }
                foreach ((($packageConf['composer.json']['extra'] ?? [])['component'] ?? [])['styles'] ?? [] as $item ) {
                    $item=$this->checkForMin($item, $packageConf['installPath']);
                    if(!empty($item) && !in_array($item, $stylesheets)) $stylesheets[]=$item;
                }
            }
    
            elseif(array_key_exists('component.json', $packageConf)) {
                $main=(array)$packageConf['component.json']['main'] ?? [];
                foreach ($main as $item) {
                    $item=$this->checkForMin($item, $packageConf['installPath']);
                    if(!empty($item) && !in_array($item, $scripts)) $scripts[]=$item;
                }
                foreach ($packageConf['component.json']['styles'] ?? [] as $item) {
                    $item=$this->checkForMin($item, $packageConf['installPath']);
                    if(!empty($item) && !in_array($item, $stylesheets)) $stylesheets[]=$item;
                }
            }
    
            elseif(array_key_exists('package.json', $packageConf)) {
                $item=$packageConf['package.json']['main'] ?? '';
                $item=$this->checkForMin($item, $packageConf['installPath']);
                if(!empty($item) && !in_array($item, $scripts)) $scripts[]=$item;
    
                $item=$packageConf['package.json']['style'] ?? '';
                $item=$this->checkForMin($item, $packageConf['installPath']);
                if(!empty($item) && !in_array($item, $stylesheets)) $stylesheets[]=$item;
            }
    
            elseif(array_key_exists('bower.json', $packageConf)) {
                $main=(array)$packageConf['bower.json']['main'] ?? [];
                foreach ($main as $item) {
                    $item=$this->checkForMin($item, $packageConf['installPath']);
                    if(preg_match('#\.js$#si', $item)) {
                        if(!empty($item) && !in_array($item, $scripts)) $scripts[]=$item;
                    } elseif(preg_match('#\.css$#si', $item)) {
                        if(!empty($item) && !in_array($item, $stylesheets)) $stylesheets[]=$item;
                    }
                }
            }
    
    
            $aPackageLoadList[$packageName]['scripts']=$scripts;
            $aPackageLoadList[$packageName]['stylesheets']=$stylesheets;
            
            foreach (array_reverse($scripts) as $item) {
                ($this->inlineScript)()->prependFile(
                    "ruga/vendorasset/{$packageName}/{$item}"
                );
            }
            foreach (array_reverse($stylesheets) as $item) {
                ($this->headLink)()->prependStylesheet(
                    "ruga/vendorasset/{$packageName}/{$item}"
                );
            }
        }
    
    
//        echo PHP_EOL . '<!--' . PHP_EOL;
//        echo print_r($aPackageLoadList, true);
//        echo PHP_EOL . '-->' . PHP_EOL;
    
        
        return '<!-- ' . print_r($aPackageLoadList, true) . ' -->';
    }
    
}