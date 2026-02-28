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
        Schema::create('warranty_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers');
                
            $table->foreignId('user_id')
                ->constrained('users'); // Quien creó la solicitud

            // Es nullable porque solo se asigna cuando se "interpreta como garantía aceptada"
            $table->foreignId('factory_id')
                ->nullable()
                ->constrained('warranty_factories');

            $table->unsignedInteger('factory_sequence')
                ->nullable()
                ->comment('Secuencia única por fábrica para carpetas/evidencias (ej. 0001)');

            $table->string('shipping_city')->nullable();
            $table->string('shipping_address')->nullable();
            $table->date('damage_date')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('internal_code')->nullable();
            $table->string('model')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('failure_description');

            $table->timestamps();

            // --- RESTRICCIÓN ÚNICA COMPUESTA ---
            // Garantiza que no existan dos solicitudes con el mismo ID de fábrica y el mismo número de secuencia
            $table->unique(['factory_id', 'factory_sequence'], 'unique_factory_sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_requests');
    }
};
