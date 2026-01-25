<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', [
                'public_sector',
                'aluminum_plastic_angles_sheet_door',
                'aluminum_iron_angles_sheet_door',
                'aluminum_iron_angles_wood_door',
                'full_wood'
            ])->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', [
                'laptop','mobile','accessory','spare','service'
            ])->change();
        });
    }

};
