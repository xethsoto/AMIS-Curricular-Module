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
        Schema::create('proposals_has_types', function (Blueprint $table) {
            $table->foreignId('prop_id')->constrained('proposals');
            $table->string('prop_action')->unique();
            $table->text('rationale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals_has_types');
    }
};
