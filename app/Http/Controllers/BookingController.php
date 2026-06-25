<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user_logged = Auth::user();

        $harga_weekday = 50000;
        $harga_weekend = 60000;

        $r = DB::table('paket_bermain')
            ->whereIn('nama_paket', ['weekday', 'weekend'])
            ->get();

        foreach ($r as $row) {
            if ($row->nama_paket === 'weekday') {
                $harga_weekday = $row->harga;
            }
            if ($row->nama_paket === 'weekend') {
                $harga_weekend = $row->harga;
            }
        }

        return view('booking', compact('user_logged', 'harga_weekday', 'harga_weekend'));
    }

    public function getPaketId(Request $request)
    {
        $type = $request->query('type');
        $paket = DB::table('paket_bermain')->where('nama_paket', $type)->first();
        return response()->json($paket ? $paket->id : 0);
    }

    public function prosesBooking(Request $request)
    {
        $user_id = Auth::id();

        $id = DB::table('booking')->insertGetId([
            'user_id' => $user_id,
            'paket_id' => $request->input('paket_id'),
            'tanggal_booking' => $request->input('tanggal_booking'),
            'jam_booking' => $request->input('jam_booking'),
            'jumlah_orang' => $request->input('jumlah_orang'),
            'total_harga' => $request->input('total_harga'),
            'is_open_match' => $request->input('is_open_match'),
            'maks_slot_open' => $request->input('maks_slot_open'),
            'catatan_open_match' => $request->input('catatan_open_match'),
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($id);
    }
}