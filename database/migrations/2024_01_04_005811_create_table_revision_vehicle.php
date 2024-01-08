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
            $table->decimal('value', $precision = 8, $scale = 2);
            $table->text('description');
            $table->boolean('is_done')->default(false);
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
