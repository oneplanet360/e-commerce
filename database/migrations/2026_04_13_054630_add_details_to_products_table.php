<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->after('slug')->nullable();
            $table->string('brand')->after('sku')->nullable();
            $table->text('short_description')->after('brand')->nullable();
            $table->json('specifications')->after('description')->nullable();
            $table->json('features')->after('specifications')->nullable();
            $table->string('meta_title')->after('status')->nullable();
            $table->text('meta_description')->after('meta_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'brand', 'short_description', 'specifications', 'features', 'meta_title', 'meta_description']);
        });
    }
};
