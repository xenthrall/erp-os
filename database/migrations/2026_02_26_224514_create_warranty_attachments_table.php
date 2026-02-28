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
        Schema::create('warranty_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warranty_request_id')
                ->constrained('warranty_requests')
                ->cascadeOnDelete();
                
            $table->string('path'); // Ruta del archivo en el storage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_attachments');
    }
};
