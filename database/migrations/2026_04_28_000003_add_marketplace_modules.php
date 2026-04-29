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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->unsignedInteger('total_items')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0)->after('description');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->nullOnDelete();
            $table->string('color')->nullable()->after('brand');
            $table->string('size')->nullable()->after('color');
            $table->integer('cost_usd')->nullable()->after('discount_price');
            $table->string('currency', 10)->default('USD')->after('cost_usd');
            $table->decimal('tax_rate', 5, 2)->nullable()->after('currency');
            $table->string('tax_id')->nullable()->after('tax_rate');
            $table->decimal('width', 8, 2)->nullable()->after('tax_id');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->integer('weight_grams')->nullable()->after('height');
            $table->integer('shipping_fees')->nullable()->after('weight_grams');
            $table->boolean('is_enabled')->default(true)->after('status');
            $table->boolean('is_template')->default(false)->after('is_enabled');
            $table->timestamp('date_added')->nullable()->after('is_template');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->unsignedTinyInteger('rating');
            $table->date('date_added')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('review_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
            $table->dropColumn([
                'color',
                'size',
                'cost_usd',
                'currency',
                'tax_rate',
                'tax_id',
                'width',
                'height',
                'weight_grams',
                'shipping_fees',
                'is_enabled',
                'is_template',
                'date_added',
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn('sort_order');
        });

        Schema::dropIfExists('brands');
    }
};
