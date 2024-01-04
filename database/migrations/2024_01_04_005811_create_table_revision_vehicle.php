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
        Schema::create('revision_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('type_revision');
            $table->string('value');
            $table->string('description');
            $table->string('is_done')->default(false);
            $table->foreignId('owner_id')->contrained();
            $table->foreignId('vehicle_id')->contrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_vehicles');
    }
};
