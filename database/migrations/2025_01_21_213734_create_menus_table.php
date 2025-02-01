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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('url')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);


            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('SET NULL');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('SET NULL');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
