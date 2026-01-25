<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {

            // نشيل الـ FK بس
            $table->dropForeign(['quotation_id']);
        });

        Schema::table('contracts', function (Blueprint $table) {

            // نعدل العمود نفسه
            $table->unsignedBigInteger('quotation_id')
                  ->nullable()
                  ->change();

            // نرجع الـ FK
            $table->foreign('quotation_id')
                  ->references('id')
                  ->on('quotations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {

            $table->dropForeign(['quotation_id']);

            $table->unsignedBigInteger('quotation_id')
                  ->nullable(false)
                  ->change();

            $table->foreign('quotation_id')
                  ->references('id')
                  ->on('quotations')
                  ->cascadeOnDelete();
        });
    }
};
