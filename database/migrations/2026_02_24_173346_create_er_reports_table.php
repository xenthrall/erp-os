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
        Schema::create('er_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('er_type_id')
                ->constrained('er_types')
                ->restrictOnDelete();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete();

            $table->string('status')->nullable();

            $table->date('event_date');
            $table->text('description');
            $table->text('solution')->nullable();
            $table->json('references')->nullable();

            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('er_reports');
    }
};
