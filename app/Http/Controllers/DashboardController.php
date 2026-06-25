<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $first_name = explode(' ', $user->nama_lengkap)[0];

        $total = DB::table('booking')
            ->where('user_id', $user_id)
            ->where('status', 'selesai')
            ->count();

        $best_data = DB::table('hasil_balapan')
            ->where('user_id', $user_id)
            ->min('total_lap');

        $best_time = ($best_data && $best_data > 0) ? number_format($best_data, 3) . ' detik' : 'Belum Ada';

        $aktif_count = DB::table('booking')
            ->where('user_id', $user_id)
            ->where('status', 'aktif')
            ->count();

        $next_bookings = DB::table('booking')
            ->join('paket_bermain', 'booking.paket_id', '=', 'paket_bermain.id')
            ->where('booking.user_id', $user_id)
            ->where('booking.status', 'aktif')
            ->select('booking.*', 'paket_bermain.nama_paket', 'paket_bermain.durasi_menit')
            ->orderBy('booking.tanggal_booking', 'asc')
            ->orderBy('booking.jam_booking', 'asc')
            ->get();

        $open_matches = DB::table('booking')
            ->join('users', 'booking.user_id', '=', 'users.id')
            ->join('paket_bermain', 'booking.paket_id', '=', 'paket_bermain.id')
            ->select(
                'booking.*',
                'users.nama_lengkap',
                'paket_bermain.nama_paket',
                'paket_bermain.durasi_menit',
                DB::raw('(SELECT COUNT(*) FROM peserta_open_match WHERE peserta_open_match.booking_id = booking.id AND peserta_open_match.status = "bergabung") as jumlah_peserta')
            )
            ->where('booking.is_open_match', 1)
            ->where('booking.status', 'aktif')
            ->where('booking.user_id', '!=', $user_id)
            ->where(function($query) {
                $query->whereRaw('booking.tanggal_booking > CURDATE()')
                      ->orWhere(function($q) {
                          $q->whereRaw('booking.tanggal_booking = CURDATE()')
                            ->whereRaw('TIMEDIFF(booking.jam_booking, CURTIME()) > "03:00:00"');
                      });
            })
            ->whereRaw('(SELECT COUNT(*) FROM peserta_open_match WHERE peserta_open_match.booking_id = booking.id AND peserta_open_match.status = "bergabung") < booking.maks_slot_open')
            ->orderBy('booking.tanggal_booking', 'asc')
            ->orderBy('booking.jam_booking', 'asc')
            ->limit(3)
            ->get();

        $partisipasi = DB::table('peserta_open_match')
            ->join('booking', 'peserta_open_match.booking_id', '=', 'booking.id')
            ->join('users', 'booking.user_id', '=', 'users.id')
            ->join('paket_bermain', 'booking.paket_id', '=', 'paket_bermain.id')
            ->where('peserta_open_match.user_id', $user_id)
            ->where('peserta_open_match.status', 'bergabung')
            ->where('booking.status', 'aktif')
            ->select('booking.tanggal_booking', 'booking.jam_booking', 'users.nama_lengkap', 'paket_bermain.nama_paket', 'peserta_open_match.id as partisipasi_id')
            ->orderBy('booking.tanggal_booking', 'asc')
            ->orderBy('booking.jam_booking', 'asc')
            ->get();

        $partisipasi_count = $partisipasi->count();

        return view('dashboard', compact(
            'user',
            'first_name',
            'total',
            'best_time',
            'aktif_count',
            'next_bookings',
            'open_matches',
            'partisipasi',
            'partisipasi_count'
        ));
    }
}