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
        Schema::create('warranty_batch_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warranty_request_id')
                ->constrained('warranty_requests')
                ->restrictOnDelete();
            $table->foreignId('warranty_batch_id')
                ->constrained('warranty_batches')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity_assigned');
            $table->timestamps();

            //Esto garantiza que una garantía solo aparezca una vez por lote
            $table->unique(
                ['warranty_request_id', 'warranty_batch_id'],
                'unique_request_batch'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_batch_items');
    }
};
