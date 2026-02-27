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
        Schema::create('warranty_batches', function (Blueprint $table) {
            $table->id();
            
            //rELACION CON EL CLIENTE
            $table->foreignId('customer_id')
                ->constrained('customers');

            $table->string('status')->default('open');
            $table->string('out_sequence')->nullable();
            $table->string('servientrega_guide')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_batches');
    }
};
