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
        Schema::create('er_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('er_report_id')
                ->constrained('er_reports')
                ->cascadeOnDelete();

            $table->string('path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('er_attachments');
    }
};
