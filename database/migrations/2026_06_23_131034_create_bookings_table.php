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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('paket_id')->nullable()->constrained('paket_bermain')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_booking')->nullable();
            $table->time('jam_booking')->nullable();
            $table->integer('jumlah_orang')->nullable();
            $table->integer('total_harga')->nullable();
            $table->string('bukti_transfer', 255)->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
