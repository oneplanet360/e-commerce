<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('marketing_banners') && Schema::hasColumn('marketing_banners', 'background_color')) {
            Schema::table('marketing_banners', function (Blueprint $table) {
                $table->dropColumn('background_color');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('marketing_banners') && !Schema::hasColumn('marketing_banners', 'background_color')) {
            Schema::table('marketing_banners', function (Blueprint $table) {
                $table->string('background_color')->nullable()->after('description');
            });
        }
    }
};
