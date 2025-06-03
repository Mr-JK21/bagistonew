<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Repositories\ChannelRepository;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        $now = now()->toDateTimeString();

        $channels = app(ChannelRepository::class)->with('locales')->all();

        /*
        |--------------------------------------------------------------------------
        | General Configuration
        |--------------------------------------------------------------------------
        */
        $configurations = [
            [
                'code'         => 'marketplace.settings.general.lines_in_street_address',
                'value'        => 1,
                'channel_code' => null,
            ],
        ];

        foreach ($channels as $channel) {
            $configurations = array_merge($configurations, [
                [
                    'code'         => 'marketplace.settings.general.status',
                    'value'        => 0,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.general.admin_commission_percentage',
                    'value'        => 20,
                    'channel_code' => $channel->code,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Product Configuration
        |--------------------------------------------------------------------------
        */
        foreach ($channels as $channel) {
            $configurations = array_merge($configurations, [
                [
                    'code'         => 'marketplace.settings.product.approval_required',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.product.seller_can_assign',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.product.seller_can_create',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.product.show_progress_bar',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.product.flag_enabled',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Seller Configuration
        |--------------------------------------------------------------------------
        */
        foreach ($channels as $channel) {
            $configurations = array_merge($configurations, [
                [
                    'code'         => 'marketplace.settings.seller.approval_required',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.can_create_invoice',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.can_create_shipment',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.can_cancel_order',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.enable_minimum_order_amount',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.flag_enabled',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.show_red_flag',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.seller.red_flag_limit',
                    'value'        => 10,
                    'channel_code' => $channel->code,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Featured Sellers Configuration
        |--------------------------------------------------------------------------
        */
        foreach ($channels as $channel) {
            $configurations = array_merge($configurations, [
                [
                    'code'         => 'marketplace.settings.featured_sellers.status',
                    'value'        => 1,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.featured_sellers.limit_count',
                    'value'        => 12,
                    'channel_code' => $channel->code,
                ], [
                    'code'         => 'marketplace.settings.featured_sellers.popularity_criteria',
                    'value'        => 'all',
                    'channel_code' => $channel->code,
                ],
            ]);
        }

        $configurations = array_map(function ($configuration) use ($now) {
            $configuration = array_merge($configuration, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return $configuration;
        }, $configurations);

        DB::table('core_config')->insert($configurations);

        /*
        |--------------------------------------------------------------------------
        | Landing Page Configuration
        |--------------------------------------------------------------------------
        */
        foreach ($channels as $channel) {
            foreach ($channel->locales as $locale) {
                $configurations = [
                    [
                        'code'         => 'marketplace.settings.landing_page.banner_title',
                        'value'        => trans('marketplace::app.seeders.configurations.banner-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.banner_description',
                        'value'        => trans('marketplace::app.seeders.configurations.banner-description', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.banner_btn_title',
                        'value'        => trans('marketplace::app.seeders.configurations.banner-btn-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.banner_image',
                        'value'        => 'configuration/DyBS0HBGRGW147DvZKTdgdiSiaWf6kHPgLK6qQFm.webp',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.community_count',
                        'value'        => 1000,
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.business_hour',
                        'value'        => '24x7',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.payment_duration',
                        'value'        => 15,
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.serviceable_pincode',
                        'value'        => 1000,
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_title',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_description',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-description', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_image',
                        'value'        => 'configuration/A9BsGYfcgCioTpGbmvnuSDR2BNIGf9hxTxpo1x3B.webp',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box1_icon',
                        'value'        => 'configuration/IWddepraaapncnfs8BxLVCPFaFv2joaCbNxRoKpQ.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box1_title',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box1-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box1_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box1-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box2_icon',
                        'value'        => 'configuration/ffSBdy4Bsh3crYXSMMxoQEwBsf7jnEA4cRBJzLpC.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box2_title',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box2-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box2_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box2-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box3_icon',
                        'value'        => 'configuration/LP9XZ9XfXPMoTq3rWra0ndEngVUdAXDgGDUvKUuK.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box3_title',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box3-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box3_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box3-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box4_icon',
                        'value'        => 'configuration/rRXj8cIvGt1ym9DKFuCZoEgrPAT01lfPG7ZfilLk.png',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box4_title',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box4-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.feature_box4_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.feature-box4-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_description',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-description', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step1_icon',
                        'value'        => 'configuration/eXlLe5OmdaQfWpV6i4kDi00ubgeVzdl7tyivVCw6.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step1_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step1-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step1_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step1-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step2_icon',
                        'value'        => 'configuration/wePx7Gu2ZB0yNxaVTnQVfGO5e9SvkRKfSAmBRHDc.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step2_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step2-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step2_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step2-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step3_icon',
                        'value'        => 'configuration/w9ovY22tcbqM99HsnVd4fbFcCxkssF54E2Q5w7zr.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step3_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step3-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step3_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step3-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step4_icon',
                        'value'        => 'configuration/0bVS9AXCfEFE3n57Zx2axZbzdCYvJ8HSGVc3LlVn.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step4_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step4-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step4_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step4-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step5_icon',
                        'value'        => 'configuration/rYld3h3AMAQcHFWWQkI7DwrCoOD1b2cR4n9U7bZW.svg',
                        'channel_code' => $channel->code,
                        'locale_code'  => null,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step5_title',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step5-title', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ], [
                        'code'         => 'marketplace.settings.landing_page.journey_step5_desc',
                        'value'        => trans('marketplace::app.seeders.configurations.journey-step5-desc', [], $locale->code),
                        'channel_code' => $channel->code,
                        'locale_code'  => $locale->code,
                    ],
                ];

                $configurations = array_map(function ($configuration) use ($now) {
                    $configuration = array_merge($configuration, [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    return $configuration;
                }, $configurations);

                DB::table('core_config')->insert($configurations);
            }
        }
    }
}
