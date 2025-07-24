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
        Schema::create('service_assets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('serviceId')->constrained('services','id');
            $table->string('resource_name');
            $table->jsonb('metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_assets');
    }
};
