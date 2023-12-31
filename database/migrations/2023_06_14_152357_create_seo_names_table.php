<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->morphs("object");
            $table->string("parent");
            $table->string("controller");
            $table->string("action");
            $table->string('path')->nullable();
            $table->char('locale', 2)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_names');
    }
};
