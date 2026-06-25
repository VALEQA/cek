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
    // Ubah namanya menjadi 'peserta_open_match' (tanpa akhiran 'es')
    Schema::create('peserta_open_match', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
        $table->string('status')->default('bergabung'); // Karena di query kamu ada status = "bergabung"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_open_matches');
    }
};
