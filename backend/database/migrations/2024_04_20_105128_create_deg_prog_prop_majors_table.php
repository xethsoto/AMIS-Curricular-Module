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
        Schema::create('deg_prog_prop_majors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('new_deg_prog')->constrained('deg_prog_institutions')->cascadeOnDelete();
            $table->string('name')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deg_prog_prop_majors');
    }
};
