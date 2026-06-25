<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaOpenMatch extends Model
{
    // Tambahkan baris ini:
    protected $table = 'peserta_open_match';

    protected $fillable = ['booking_id', 'user_id', 'status'];
}