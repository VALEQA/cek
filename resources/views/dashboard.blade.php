<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoKart Racing - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style-dashboard.css') }}">
    <style>
        .aksi-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .stats {
            grid-template-columns: repeat(4, 1fr);
        }
        .om-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
        .om-card { border-left: 4px solid var(--primary); transition: all 0.3s ease; }
        .om-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
        .om-card .om-host { font-weight: 700; font-size: 1rem; margin-bottom: 0.3rem; }
        .om-card .om-info { font-size: 0.85rem; color: var(--text-gray); margin-bottom: 0.2rem; }
        .om-card .om-slot { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600; margin-top: 0.5rem; }
        .om-card .om-slot.available { background: #d1fae5; color: #065f46; }
        .om-badge { display: inline-block; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; margin-left: 6px; }
        .om-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .om-section-header a { font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600; }
        .om-section-header a:hover { text-decoration: underline; }
        .om-empty { text-align: center; padding: 2rem; color: var(--text-gray); }

        @media (max-width: 1200px) {
            .aksi-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .aksi-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="brand">
                <h2>GoKart Racing</h2>
                <p>Booking System</p>
            </div>
            <ul class="nav-menu">
                <li><a href="{{ route('dashboard') }}" class="nav-link active">Dashboard</a></li>
                <li><a href="{{ route('booking') }}" class="nav-link">Booking</a></li>
                <li><a href="#" class="nav-link">Open Match</a></li>
                <li><a href="#" class="nav-link">Riwayat Booking</a></li>
                <li><a href="#" class="nav-link">Hasil Balapan</a></li>
                <li><a href="#" class="nav-link">Leaderboard</a></li>
                <li><a href="#" class="nav-link">Pembayaran</a></li>
                <li><a href="#" class="nav-link">Profil Saya</a></li>
            </ul>       
        </div>

        <div class="sidebar-bottom">
            <div class="sidebar-user">
                <p><strong>{{ $user->nama_lengkap }}</strong></p>
                <small>{{ $user->email }}</small>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button class="logout-btn" onclick="if(confirm('Keluar dari sistem?')) document.getElementById('logout-form').submit();">Logout</button>
        </div>
    </aside>

    <main class="main">
        <header class="header">
            <h1>Halo, {{ $first_name }}!</h1>
            <p>Siap untuk memecahkan rekor waktu hari ini? 🏁</p>
        </header>

        <section class="stats">
            <div class="card stat-card">
                <p>Total Bermain</p>
                <h3 class="stat-number">{{ (int)$total }}</h3>
            </div>

            <div class="card stat-card">
                <p>Waktu Terbaik (1 Lap)</p>
                <h3 class="stat-number">{{ $best_time }}</h3>
            </div>

            <div class="card stat-card">
                <p>Booking Aktif</p>
                <h3 class="stat-number">{{ (int)$aktif_count }}</h3>
            </div>

            <div class="card stat-card">
                <p>Open Match Diikuti</p>
                <h3 class="stat-number">{{ (int)$partisipasi_count }}</h3>
            </div>
        </section>

        <section class="booking">
            <h2>Booking Berikutnya</h2>
            <div class="booking-list">
                @if(count($next_bookings) > 0)
                    @foreach($next_bookings as $row)
                        <div class="card booking-box" style="margin-bottom: 1rem;">
                            <div class="booking-left">
                                <p class="booking-date">
                                    📅 {{ $row->tanggal_booking ? date('d M Y', strtotime((string)$row->tanggal_booking)) : '-' }} | 
                                    ⏰ {{ $row->jam_booking ? date('H:i', strtotime((string)$row->jam_booking)) : '-' }}
                                </p>
                                <div class="booking-detail">
                                    <span><strong>Paket:</strong> {{ $row->nama_paket }}</span>
                                    <span><strong>Durasi:</strong> {{ (int)$row->durasi_menit }} Menit</span>
                                    <span><strong>Pemain:</strong> {{ (int)$row->jumlah_orang }} Orang</span>
                                    @if(!empty($row->is_open_match))
                                        <span class="om-badge" style="margin-left:0;">🟢 OPEN MATCH</span>
                                    @endif
                                </div>
                            </div>
                            <div class="booking-right">
                                <button class="btn btn-secondary" onclick="window.location.href='#'">
                                    Detail
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card booking-box">
                        <p class="empty-state">Belum ada jadwal balapan aktif. Yuk, booking sekarang!</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="aksi">
            <h2>Aksi Cepat</h2>
            <div class="aksi-container">
                <div class="card aksi-box clickable" onclick="window.location.href='{{ route('booking') }}'">
                    <h3>Booking Sekarang</h3>
                    <p>Pilih jadwal dan mulai balapan</p>
                </div>

                <div class="card aksi-box clickable" onclick="window.location.href='#'">
                    <h3>Lihat Leaderboard</h3>
                    <p>Cek ranking pemain terbaik</p>
                </div>

                <div class="card aksi-box clickable" onclick="window.location.href='#'">
                    <h3>Profil Saya</h3>
                    <p>Kelola data diri dan keamanan sandi</p>
                </div>

                <div class="card aksi-box clickable" onclick="window.location.href='#'">
                    <h3>Lisensi Pembalap</h3>
                    <p>Lihat status nomor lisensi balap Anda</p>
                </div>
            </div>
        </section>

        <section>
            <div class="om-section-header">
                <h2>🏁 Open Match Tersedia</h2>
                <a href="#">Lihat Semua &rarr;</a>
            </div>
            <div class="om-grid">
                @if(count($open_matches) > 0)
                    @foreach($open_matches as $om)
                        @php
                            $host_name = $om->nama_lengkap ? explode(' ', (string)$om->nama_lengkap)[0] : 'Driver';
                            $slot_sisa = (int)($om->maks_slot_open ?? 0) - (int)($om->jumlah_peserta ?? 0);
                        @endphp
                        <div class="card om-card">
                            <div class="om-host">
                                {{ $host_name }}
                                <span class="om-badge">HOST</span>
                            </div>
                            <div class="om-info">📅 {{ $om->tanggal_booking ? date('d M Y', strtotime((string)$om->tanggal_booking)) : '-' }} | ⏰ {{ $om->jam_booking ? date('H:i', strtotime((string)$om->jam_booking)) : '-' }} WIB</div>
                            <div class="om-info">🏎️ {{ $om->nama_paket }} ({{ (int)$om->durasi_menit }} menit)</div>
                            @if(!empty($om->catatan_open_match))
                                <div class="om-info">💬 <em>{{ $om->catatan_open_match }}</em></div>
                            @endif
                            <span class="om-slot available">👥 {{ $slot_sisa }} slot tersisa</span>
                        </div>
                    @endforeach
                @else
                    <div class="card om-empty" style="grid-column: 1/-1;">
                        <p>🏁 Belum ada Open Match saat ini. <a href="#" style="color:var(--primary);">Cek nanti</a> atau <a href="{{ route('booking') }}" style="color:var(--primary);">buat booking baru!</a></p>
                    </div>
                @endif
            </div>
        </section>

        @if($partisipasi_count > 0)
        <section style="margin-top:1.5rem;">
            <h2>🤝 Partisipasi Open Match Saya</h2>
            <div class="booking-list" style="margin-top:1rem;">
                @foreach($partisipasi as $p)
                    <div class="card booking-box" style="margin-bottom:1rem;border-left-color:#10b981;">
                        <div class="booking-left">
                            <p class="booking-date">
                                📅 {{ $p->tanggal_booking ? date('d M Y', strtotime((string)$p->tanggal_booking)) : '-' }} | 
                                ⏰ {{ $p->jam_booking ? date('H:i', strtotime((string)$p->jam_booking)) : '-' }} WIB
                            </p>
                            <div class="booking-detail">
                                <span><strong>Host:</strong> {{ $p->nama_lengkap ? explode(' ', (string)$p->nama_lengkap)[0] : 'Driver' }}</span>
                                <span><strong>Paket:</strong> {{ $p->nama_paket }}</span>
                            </div>
                        </div>
                        <div class="booking-right">
                            <button class="btn btn-secondary" onclick="window.location.href='#'">
                                Detail
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

    </main>
</div>

<script src="{{ asset('script.js') }}"></script>
</body>
</html>