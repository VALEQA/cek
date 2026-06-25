<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $rekor = DB::table('hasil_balapan')
            ->join('users', 'hasil_balapan.user_id', '=', 'users.id')
            ->where('hasil_balapan.total_lap', '<', 99.999)
            ->select('hasil_balapan.*', 'users.nama_lengkap')
            ->orderBy('hasil_balapan.total_lap', 'asc')
            ->first();

        $has_record = false;
        $record_time = "15.351";
        $sector_1 = "4.893";
        $sector_2 = "5.371";
        $sector_3 = "5.267";
        $driver_name = "Belum Ada Record";

        if ($rekor) {
            $has_record = true;
            $record_time = number_format($rekor->total_lap, 3, '.', '');
            $sector_1 = number_format($rekor->sektor_1, 3, '.', '');
            $sector_2 = number_format($rekor->sektor_2, 3, '.', '');
            $sector_3 = number_format($rekor->sektor_3, 3, '.', '');
            $driver_name = $rekor->nama_lengkap;
        }

        $harga_weekday = 50000;
        $harga_weekend = 60000;

        $paket = DB::table('paket_bermain')
            ->whereIn('nama_paket', ['weekday', 'weekend'])
            ->get();

        foreach ($paket as $row) {
            if ($row->nama_paket === 'weekday') {
                $harga_weekday = $row->harga;
            }
            if ($row->nama_paket === 'weekend') {
                $harga_weekend = $row->harga;
            }
        }

        return view('landing', compact(
            'has_record',
            'record_time',
            'sector_1',
            'sector_2',
            'sector_3',
            'driver_name',
            'harga_weekday',
            'harga_weekend'
        ));
    }
}