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
    Schema::table('booking', function (Blueprint $table) {
        // Menambahkan kolom is_open_match (default 0 / false)
        $table->boolean('is_open_match')->default(0)->after('status'); 
        
        // Menambahkan kolom maks_slot_open (default 0)
        $table->integer('maks_slot_open')->default(0)->after('is_open_match');
    });
}

public function down(): void
{
    Schema::table('booking', function (Blueprint $table) {
        $table->dropColumn(['is_open_match', 'maks_slot_open']);
    });
}
};

