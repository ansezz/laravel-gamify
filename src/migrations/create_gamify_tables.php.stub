<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamifyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Points Table
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('point');
            $table->string('class')->nullable();
            $table->boolean('allow_duplicate')->default(false);
            $table->unsignedBigInteger('gamify_group_id');
            $table->timestamps();
        });

        // Badges Table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('class')->nullable();
            $table->unsignedBigInteger('gamify_level_id')->nullable();
            $table->unsignedBigInteger('gamify_group_id')->nullable();
            $table->timestamps();
        });

        // Group table
        Schema::create('gamify_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['badge', 'point']);
            $table->timestamps();
        });

        // Pointables  table
        Schema::create('pointables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('point_id');
            $table->morphs('pointable');
            $table->double('achieved_point')->default(0);
            $table->timestamps();
        });

        // Badgables table
        Schema::create('badgables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('badge_id');
            $table->morphs('badgable');
            $table->timestamps();
        });

        // gamify_levels tables
        Schema::create('gamify_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('badges');
        Schema::dropIfExists('gamify_groups');
        Schema::dropIfExists('points');
        Schema::dropIfExists('pointables');
        Schema::dropIfExists('badgables');
        Schema::dropIfExists('badge_level');
        Schema::dropIfExists('gamify_levels');
    }
}
