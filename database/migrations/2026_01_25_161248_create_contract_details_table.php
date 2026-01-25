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
        Schema::create('contract_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->string('title');   // اسم التفصيلة
            $table->text('value');     // التفاصيل اللي كتبها المستخدم

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_details');
    }
};
