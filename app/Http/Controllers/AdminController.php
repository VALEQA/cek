<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $total_users = DB::table('users')->where('role', 'user')->count();

        $booking_masuk = DB::table('booking')
            ->where('status', 'aktif')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->count();

        $booking_selesai = DB::table('booking')->where('status', 'selesai')->count();

        $total_pendapatan = DB::table('booking')->where('status', 'selesai')->sum('total_harga');

        $pembayaran = DB::table('booking')
            ->join('users', 'booking.user_id', '=', 'users.id')
            ->join('paket_bermain', 'booking.paket_id', '=', 'paket_bermain.id')
            ->where('booking.status', 'aktif')
            ->whereNotNull('booking.bukti_transfer')
            ->where('booking.bukti_transfer', '!=', '')
            ->select('booking.*', 'users.nama_lengkap', 'users.email', 'users.nomor_hp', 'paket_bermain.nama_paket')
            ->orderBy('booking.created_at', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'total_users',
            'booking_masuk',
            'booking_selesai',
            'total_pendapatan',
            'pembayaran'
        ));
    }

    public function prosesAksi($action, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        if ($action === 'setujui') {
            $booking = DB::table('booking')->where('id', $id)->first();
            if ($booking) {
                DB::table('booking')->where('id', $id)->update(['status' => 'selesai']);
                DB::table('users')->where('id', $booking->user_id)->increment('total_bermain');
                return redirect()->route('admin.dashboard')->with('success', 'Konfirmasi Berhasil! Booking #BK-' . $id . ' LUNAS & Data Total Bermain Berhasil Ditambahkan (+1).');
            }
        } elseif ($action === 'tolak') {
            DB::table('booking')->where('id', $id)->update([
                'status' => 'aktif',
                'bukti_transfer' => null
            ]);
            return redirect()->route('admin.dashboard')->with('error', 'Bukti Transfer #BK-' . $id . ' Ditolak! File dibersihkan.');
        }

        return redirect()->route('admin.dashboard');
    }
}