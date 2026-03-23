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
        Schema::create('manage_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->boolean('language');
            $table->boolean('age');
            $table->boolean('gender');
            $table->boolean('location');
            $table->boolean('evangelist_name');
            $table->boolean('church_evangelize');
            $table->boolean('assign_directly');
            $table->foreignId('event_id')->constrained()->cascadeOnDelete()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_follow_ups');
    }
};
