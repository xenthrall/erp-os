<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->restrictOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            
            $table->string('document_type', 10)->nullable()->default('CC');
            $table->string('document_number', 50)->unique();
            
            $table->string('phone', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->date('hire_date')->nullable();
            
            $table->string('position')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};