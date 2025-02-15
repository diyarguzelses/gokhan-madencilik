<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrderColumnInMenusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->integer('order')->nullable()->default(null)->change();
        });

        $menus = DB::table('menus')->orderBy('id')->get();
        $order = 1;
        foreach ($menus as $menu) {
            DB::table('menus')
                ->where('id', $menu->id)
                ->update(['order' => $order]);
            $order++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->integer('order')->nullable()->default(0)->change();
        });
    }
}
