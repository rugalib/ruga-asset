<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

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
            'ruga' => [
                'asset' => [
                    'rugalib/asset-jqueryui' => [
                        'scripts' => ['jquery-ui.min.js'],
                        'stylesheets' => ['jquery-ui.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5']
                    ],
                    'rugalib/asset-popperjs' => [
                        'scripts' => ['dist/umd/popper.min.js'],
                        'stylesheets' => [],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-bootstrap' => [
                        'scripts' => ['js/bootstrap.min.js'],
                        'stylesheets' => ['css/bootstrap.min.css'],
                        'require' => ['rugalib/asset-popperjs' => '^1.16']
                    ],
                    'rugalib/asset-jquery-datatables' => [
                        'scripts' => ['datatables.min.js'],
                        'stylesheets' => ['datatables.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5', 'rugalib/asset-bootstrap' => '^5.1']
                    ],
                    'rugalib/asset-fontawesome-free' => [
                        'scripts' => [],
                        'stylesheets' => ['css/all.min.css'],
                    ],
                    'rugalib/asset-momentjs' => [
                        'scripts' => ['moment.js'],
                        'stylesheets' => [],
                        'require' => [],
                    ],
                    'rugalib/asset-tempusdominus-bs4' => [
                        'scripts' => ['build/js/tempusdominus-bootstrap-4.min.js'],
                        'stylesheets' => ['build/css/tempusdominus-bootstrap-4.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5', 'rugalib/asset-bootstrap' => '^4.6', 'rugalib/asset-momentjs' => '^2.29', 'rugalib/asset-popperjs' => '^1.16']
                    ],
                    'rugalib/asset-icheck-bootstrap' => [
                        'scripts' => [],
                        'stylesheets' => ['icheck-bootstrap.min.css'],
                        'require' => ['rugalib/asset-bootstrap' => '^4.6'],
                    ],
                    'rugalib/asset-jqvmap' => [
                        'scripts' => ['dist/jquery.vmap.min.js'],
                        'stylesheets' => ['dist/jqvmap.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-overlayscrollbars' => [
                        'scripts' => ['js/jquery.overlayScrollbars.min.js'],
                        'stylesheets' => ['css/OverlayScrollbars.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-daterangepicker' => [
                        'scripts' => ['daterangepicker.js'],
                        'stylesheets' => ['daterangepicker.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-summernote' => [
                        'scripts' => ['dist/summernote-bs4.min.js'],
                        'stylesheets' => ['dist/summernote-bs4.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5', 'rugalib/asset-bootstrap' => '^4.6'],
                    ],
                    'rugalib/asset-chartjs' => [
                        'scripts' => ['dist/Chart.min.js'],
                        'stylesheets' => [],
                        'require' => ['rugalib/asset-jquery' => '^3.5', 'rugalib/asset-bootstrap' => '^4.6'],
                    ],
                    'rugalib/asset-jquery-knob' => [
                        'scripts' => ['dist/jquery.knob.min.js'],
                        'stylesheets' => [],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-jquery-sparkline' => [
                        'scripts' => ['jquery.sparkline.min.js'],
                        'stylesheets' => [],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-select2' => [
                        'scripts' => ['dist/js/select2.full.min.js'],
                        'stylesheets' => ['dist/css/select2.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-alertifyjs' => [
                        'scripts' => ['build/alertify.min.js'],
                        'stylesheets' => ['build/css/alertify.min.css', 'build/css/themes/default.min.css', 'build/css/themes/bootstrap.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                    'rugalib/asset-filepond' => [
                        'scripts' => ['dist/filepond.min.js', 'locale/de-de.js'],
                        'stylesheets' => ['dist/filepond.min.css'],
                        'require' => ['rugalib/asset-jquery' => '^3.5'],
                    ],
                ],
            ],
        ];
    }
}
