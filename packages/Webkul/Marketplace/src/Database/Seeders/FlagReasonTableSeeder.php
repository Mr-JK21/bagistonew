<?php

namespace Webkul\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Marketplace\Enums\FlagReasonType;

class FlagReasonTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('mp_flag_reasons')->delete();

        DB::table('mp_flag_reason_translations')->delete();

        $defaultLocale = config('app.locale');

        $localeIds = DB::table('channel_locales')->pluck('locale_id')->toArray();

        $locales = DB::table('locales')->whereIn('id', $localeIds)->pluck('code')->toArray();

        $now = now();

        $reasons = [
            [
                'id'         => 1,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.duplicate-product', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 2,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.damaged-product', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 3,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.poor-product-quality', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 4,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.over-price-product', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 5,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.missing-product-parts', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 6,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.receive-wrong-product', [], $defaultLocale),
                'type'       => FlagReasonType::PRODUCT->value,
            ], [
                'id'         => 7,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.duplicate-product-sold', [], $defaultLocale),
                'type'       => FlagReasonType::SELLER->value,
            ], [
                'id'         => 8,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.damaged-product-sold', [], $defaultLocale),
                'type'       => FlagReasonType::SELLER->value,
            ], [
                'id'         => 9,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.poor-product-quality-sold', [], $defaultLocale),
                'type'       => FlagReasonType::SELLER->value,
            ], [
                'id'         => 10,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.over-price-product-sold', [], $defaultLocale),
                'type'       => FlagReasonType::SELLER->value,
            ], [
                'id'         => 11,
                'admin_name' => trans('marketplace::app.seeders.flag-reasons.wrong-product-sold', [], $defaultLocale),
                'type'       => FlagReasonType::SELLER->value,
            ],
        ];

        $reasons = array_map(function ($reason) use ($now) {
            return array_merge($reason, [
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $reasons);

        DB::table('mp_flag_reasons')->insert($reasons);

        foreach ($locales as $locale) {
            DB::table('mp_flag_reason_translations')->insert([
                [
                    'mp_flag_reason_id' => 1,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.duplicate-product', [], $locale),
                ], [
                    'mp_flag_reason_id' => 2,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.damaged-product', [], $locale),
                ], [
                    'mp_flag_reason_id' => 3,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.poor-product-quality', [], $locale),
                ], [
                    'mp_flag_reason_id' => 4,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.over-price-product', [], $locale),
                ], [
                    'mp_flag_reason_id' => 5,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.missing-product-parts', [], $locale),
                ], [
                    'mp_flag_reason_id' => 6,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.receive-wrong-product', [], $locale),
                ], [
                    'mp_flag_reason_id' => 7,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.duplicate-product-sold', [], $locale),
                ], [
                    'mp_flag_reason_id' => 8,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.damaged-product-sold', [], $locale),
                ], [
                    'mp_flag_reason_id' => 9,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.poor-product-quality-sold', [], $locale),
                ], [
                    'mp_flag_reason_id' => 10,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.over-price-product-sold', [], $locale),
                ], [
                    'mp_flag_reason_id' => 11,
                    'locale'            => $locale,
                    'name'              => trans('marketplace::app.seeders.flag-reasons.wrong-product-sold', [], $locale),
                ],
            ]);
        }
    }
}
