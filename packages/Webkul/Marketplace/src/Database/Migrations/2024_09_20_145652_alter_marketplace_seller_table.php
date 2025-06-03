<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('marketplace_sellers', 'name')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'email')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('email')->unique()->after('name');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'password')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('password')->after('email');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'commission_enable')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->boolean('commission_enable')->default(0)->after('privacy_policy');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'commission_percentage')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->decimal('commission_percentage', 12, 4)->default(0)->nullable()->after('commission_enable');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'min_order_amount')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('min_order_amount')->nullable()->after('commission_percentage');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'google_analytics_id')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('google_analytics_id')->nullable()->after('min_order_amount');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'allowed_product_types')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->json('allowed_product_types')->nullable()->after('google_analytics_id');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'tax_vat')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->dropColumn('tax_vat');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'customer_id')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->dropConstrainedForeignId('customer_id');
                $table->dropColumn('customer_id');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'youtube')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->dropColumn('youtube');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'instagram')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->dropColumn('instagram');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'skype')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->dropColumn('skype');
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'linked_in')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->renameColumn('linked_in', 'linkedin');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'is_suspended')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->boolean('is_suspended')->default(0)->after('is_approved');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'is_profile_completed')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->boolean('is_profile_completed')->default(0)->after('is_suspended');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'locale')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('locale')->nullable()->after('postcode');
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'parent_id')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->unsignedInteger('parent_id')
                    ->nullable()
                    ->after('is_approved');

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('marketplace_sellers')
                    ->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'marketplace_role_id')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->foreignId('marketplace_role_id')
                    ->nullable()
                    ->after('is_approved')
                    ->constrained();
            });
        }

        if (Schema::hasColumn('marketplace_sellers', 'url')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('url')->nullable()->change();
            });
        }

        if (! Schema::hasColumn('marketplace_sellers', 'address')) {
            Schema::table('marketplace_sellers', function (Blueprint $table) {
                $table->string('address')->nullable()->after('phone');
            });

            if (
                Schema::hasColumn('marketplace_sellers', 'address1')
                && Schema::hasColumn('marketplace_sellers', 'address2')
            ) {
                DB::table('marketplace_sellers')->update([
                    'address' => DB::raw('CONCAT(address1, CHAR(10), address2)'),
                ]);

                Schema::table('marketplace_sellers', function (Blueprint $table) {
                    $table->dropColumn(['address1', 'address2']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
