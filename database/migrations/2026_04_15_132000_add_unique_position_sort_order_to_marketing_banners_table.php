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
        // Normalize existing data so each (position, sort_order) pair is unique
        $rows = DB::table('marketing_banners')
            ->select('id', 'position', 'sort_order')
            ->orderBy('position')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $used = [];
        $maxPerPosition = [];

        foreach ($rows as $row) {
            if (!isset($used[$row->position])) {
                $used[$row->position] = [];
                $maxPerPosition[$row->position] = -1;
            }

            $current = (int) $row->sort_order;

            if (!in_array($current, $used[$row->position], true)) {
                $used[$row->position][] = $current;
                if ($current > $maxPerPosition[$row->position]) {
                    $maxPerPosition[$row->position] = $current;
                }
                continue;
            }

            $newOrder = $maxPerPosition[$row->position] + 1;

            DB::table('marketing_banners')
                ->where('id', $row->id)
                ->update(['sort_order' => $newOrder]);

            $used[$row->position][] = $newOrder;
            $maxPerPosition[$row->position] = $newOrder;
        }

        Schema::table('marketing_banners', function (Blueprint $table) {
            $table->unique(['position', 'sort_order'], 'marketing_banners_position_sort_order_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_banners', function (Blueprint $table) {
            $table->dropUnique('marketing_banners_position_sort_order_unique');
        });
    }
};
