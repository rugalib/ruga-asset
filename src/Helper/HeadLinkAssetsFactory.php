<?php

declare(strict_types=1);

namespace Ruga\Asset\Helper;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class HeadLinkAssetsFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return HeadLinkAssets
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): HeadLinkAssets
    {
        \Ruga\Log::functionHead();
        $config = $container->get('config')['ruga']['asset'] ?? [];
        if (!$container instanceof AbstractPluginManager) {
            // laminas-servicemanager v3. v2 passes the helper manager directly.
            $container = $container->get(\Laminas\View\HelperPluginManager::class);
        }
        return new HeadLinkAssets($container->get('inlineScript'), $container->get('headLink'), $config);
    }
}
