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
        Schema::create('deg_prog_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('career');
            $table->string('college');
            $table->integer('num_of_units');
            $table->text('desc');
            $table->foreignId('prop_id')->constrained('proposals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deg_prog_institution');
    }
};
