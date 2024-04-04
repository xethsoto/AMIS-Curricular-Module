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
        Schema::create('course_prop_sem_offered', function (Blueprint $table) {
            $table->foreignId('prop_id')->constrained('course_institutions')->cascadeOnDelete();
            $table->string('sem_offered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prop_sem_offered');
    }
};
