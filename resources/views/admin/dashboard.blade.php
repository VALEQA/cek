<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoKart Admin - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style-admin.css') }}">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-top">
                <div class="brand">
                    <h2>GoKart Admin</h2>
                    <p>Management System</p>
                </div>
                <ul class="nav-menu">
                    <li><a href="{{ route('admin.dashboard') }}" class="nav-link active"><span>Dashboard</span></a></li>
                    <li><a href="#" class="nav-link"><span>Riwayat Keuangan</span></a></li>
                    <li><a href="#" class="nav-link"><span>Input Waktu Balap</span></a></li>
                    <li><a href="#" class="nav-link"><span>Lihat Leaderboard</span></a></li>
                    <li><a href="#" class="nav-link"><span>Kelola Paket</span></a></li>
                    <li><a href="#" class="nav-link"><span>Kelola Users</span></a></li>
                </ul>
            </div>
            <div class="sidebar-bottom">
                <div class="sidebar-user">
                    <p><strong>Admin Panel</strong></p>
                    <small>Race Director</small>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <button class="logout-btn" onclick="if(confirm('Keluar dari panel admin?')) document.getElementById('logout-form').submit();">
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        <main class="main">
            <header class="header">
                <h1>Overview Dashboard</h1>
                <p>Verifikasi pembayaran sirkuit racing hub secara real-time</p>
            </header>

            @if(session('success'))
                <div class="alert alert-success" style="padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <section class="stats-grid">
                <div class="stat-card">
                    <span class="stat-icon">👥</span>
                    <div>
                        <h3>Total Racers</h3>
                        <p class="stat-number">{{ $total_users }} <span class="unit">orang</span></p>
                    </div>
                </div>
                <div class="stat-card alert-card">
                    <span class="stat-icon">🔔</span>
                    <div>
                        <h3>Butuh Validasi</h3>
                        <p class="stat-number">{{ $booking_masuk }} <span class="unit">transaksi</span></p>
                    </div>
                </div>
                <div class="stat-card success-card">
                    <span class="stat-icon">✅</span>
                    <div>
                        <h3>Sesi Selesai</h3>
                        <p class="stat-number">{{ $booking_selesai }} <span class="unit">sesi</span></p>
                    </div>
                </div>
                <div class="stat-card money-card">
                    <span class="stat-icon">💰</span>
                    <div>
                        <h3>Total Omset</h3>
                        <p class="stat-number">Rp {{ number_format($total_pendapatan ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </section>

            <section class="card table-container" style="margin-top: 2rem;">
                <h3 style="border-left: 4px solid var(--dark); padding-left: 10px; margin-bottom: 1.5rem;">Persetujuan Transaksi Masuk</h3>
                
                <div style="overflow-x: auto;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID Booking</th>
                                <th>Nama Pembalap</th>
                                <th>Paket Pilihan</th>
                                <th style="text-align: right;">Total Tagihan</th>
                                <th style="text-align: center;">Bukti Upload</th>
                                <th style="text-align: center;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($pembayaran) == 0)
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--gray); padding: 3rem; font-style: italic;">
                                    Belum ada kiriman bukti transfer baru dari pembalap yang perlu divalidasi.
                                </td>
                            </tr>
                            @else
                                @foreach($pembayaran as $row)
                                <tr>
                                    <td><strong>#BK-{{ $row->id }}</strong></td>
                                    <td>
                                        <strong>{{ $row->nama_lengkap }}</strong><br>
                                        <small style="color: var(--gray);">HP: {{ $row->nomor_hp }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-paket">{{ $row->nama_paket }}</span><br>
                                        <small>{{ date('d M Y', strtotime($row->tanggal_booking)) }} - Pukul {{ date('H:i', strtotime($row->jam_booking)) }}</small>
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: #1d3557;">
                                        Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ asset('pembayaran/uploads/' . $row->bukti_transfer) }}" target="_blank" class="view-proof-btn">Lihat Bukti</a>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.dashboard.proses', ['action' => 'setujui', 'id' => $row->id]) }}" class="btn-approve" onclick="return confirm('Apakah uang transferan user ini benar-benar sudah masuk ke rekening?')">Terima</a>
                                            <a href="{{ route('admin.dashboard.proses', ['action' => 'tolak', 'id' => $row->id]) }}" class="btn-reject" onclick="return confirm('Tolak bukti transfer ini dan minta user kirim ulang?')">Tolak</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('script.js') }}"></script>
</body>
</html>