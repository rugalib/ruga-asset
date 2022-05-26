<?php

declare(strict_types=1);

namespace Ruga\Asset;

/**
 * ConfigProvider.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * @see      https://docs.mezzio.dev/mezzio/v3/features/container/config/
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
//            'dependencies' => [
//                'services' => [],
//                'aliases' => [],
//                'factories' => [],
//                'invokables' => [],
//                'delegators' => [],
//            ],
            'view_helpers' => [
                'aliases' => [
                    'headLinkAssets' => \Ruga\Asset\Helper\HeadLinkAssets::class,
                ],
                'factories' => [
                    \Ruga\Asset\Helper\HeadLinkAssets::class => \Ruga\Asset\Helper\HeadLinkAssetsFactory::class,
                ],
            ],
        ];
    }
}
