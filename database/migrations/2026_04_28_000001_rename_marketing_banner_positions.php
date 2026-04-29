<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('marketing_banners')) {
            DB::table('marketing_banners')
                ->where('position', 'top_banner')
                ->update(['position' => 'main_banner']);

            DB::table('marketing_banners')
                ->where('position', 'ads')
                ->update(['position' => 'bottom_banner']);

            DB::statement("ALTER TABLE marketing_banners MODIFY position ENUM('main_banner', 'middle_banner', 'bottom_banner') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('marketing_banners')) {
            DB::table('marketing_banners')
                ->where('position', 'main_banner')
                ->update(['position' => 'top_banner']);

            DB::table('marketing_banners')
                ->where('position', 'bottom_banner')
                ->update(['position' => 'ads']);

            DB::statement("ALTER TABLE marketing_banners MODIFY position ENUM('top_banner', 'middle_banner', 'ads') NOT NULL");
        }
    }
};
