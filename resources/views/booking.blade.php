<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoKart Racing - Booking</title>
    <link rel="stylesheet" href="{{ asset('style-booking.css') }}">
    <style>
        .day-type-banner {
            display: flex; align-items: center; gap: 10px;
            padding: 0.7rem 1rem; border-radius: 8px;
            margin-bottom: 1.2rem; font-weight: 600; font-size: 0.95rem;
            border: 1.5px solid transparent; transition: all 0.3s ease;
        }
        .day-type-banner.weekday { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
        .day-type-banner.weekend { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .day-type-banner.none { background: var(--bg-body); color: var(--text-gray); border-color: var(--border-color); }

        .kart-counter { display: flex; align-items: center; gap: 12px; margin-top: 0.5rem; }
        .kart-counter button {
            width: 36px; height: 36px; border-radius: 8px;
            border: 1.5px solid var(--border-color); background: var(--white);
            font-size: 1.2rem; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .kart-counter button:hover { border-color: var(--primary); color: var(--primary); }
        .kart-counter button:disabled { opacity: 0.35; cursor: not-allowed; }
        .kart-count-display {
            width: 44px; height: 36px; text-align: center;
            border: 1.5px solid var(--border-color); border-radius: 8px;
            font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; justify-content: center;
        }
        .kart-dots { display: flex; gap: 6px; margin-top: 0.6rem; flex-wrap: wrap; }
        .kart-dot {
            width: 28px; height: 28px; border-radius: 6px;
            background: var(--border-color); transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center; font-size: 0.9rem;
        }
        .kart-dot.active { background: var(--primary); }

        .time-input-wrap { position: relative; }
        .time-input-wrap input[type="time"] {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--border-color);
            border-radius: 8px; font-size: 1.1rem; font-weight: 600; outline: none;
            color: var(--text-dark); background: var(--white); cursor: pointer;
        }
        .time-input-wrap input[type="time"]:focus { border-color: var(--primary); }
        .time-hint { font-size: 0.8rem; color: var(--text-gray); margin-top: 0.3rem; }
        .time-error { font-size: 0.8rem; color: #dc2626; margin-top: 0.3rem; display: none; }

        .price-breakdown { margin-top: 0.8rem; padding: 0.8rem; background: var(--bg-body); border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.88rem; }
        .price-breakdown .pb-row { display: flex; justify-content: space-between; padding: 0.2rem 0; color: var(--text-gray); }
        .price-breakdown .pb-row.total { font-weight: 700; color: var(--text-dark); border-top: 1px solid var(--border-color); margin-top: 0.4rem; padding-top: 0.4rem; }

        .form-label { font-weight: 600; font-size: 0.95rem; color: var(--text-dark); margin-bottom: 0.5rem; display: block; }

        .open-match-toggle { display: flex; align-items: center; gap: 12px; }
        .toggle-switch { position: relative; display: inline-block; width: 50px; height: 26px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc; transition: 0.3s; border-radius: 26px;
        }
        .toggle-slider:before {
            position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px;
            background-color: white; transition: 0.3s; border-radius: 50%;
        }
        .toggle-switch input:checked + .toggle-slider { background-color: var(--primary); }
        .toggle-switch input:checked + .toggle-slider:before { transform: translateX(24px); }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="brand"><h2>GoKart Racing</h2><p>Booking System</p></div>
            <ul class="nav-menu">
                <li><a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a></li>
                <li><a href="{{ route('booking') }}" class="nav-link active">Booking</a></li>
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
                <p><strong>{{ $user_logged->nama_lengkap }}</strong></p>
                <small>{{ $user_logged->email }}</small>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button class="logout-btn" onclick="if(confirm('Keluar dari sistem GoKart Racing?')) document.getElementById('logout-form').submit();">Logout</button>
        </div>
    </aside>

    <main class="main">
        <header class="page-header">
            <h1>Booking Sekarang</h1>
            <p>Pilih tanggal, jam, dan jumlah kart yang ingin Anda sewa</p>
        </header>

        <form id="formBooking">
            <input type="hidden" id="hiddenPaketId" name="paket_id" value="">
            <input type="hidden" id="hiddenTanggal" name="tanggal_booking" value="{{ date('Y-m-d') }}">
            <input type="hidden" id="hiddenJam" name="jam_booking" value="">
            <input type="hidden" id="hiddenJumlahOrang" name="jumlah_orang" value="1">
            <input type="hidden" id="hiddenTotalHarga" name="total_harga" value="0">
            <input type="hidden" id="hiddenOpenMatch" name="is_open_match" value="0">
            <input type="hidden" id="hiddenMaksSlot" name="maks_slot_open" value="0">
            <input type="hidden" id="hiddenCatatanOpen" name="catatan_open_match" value="">

            <div class="booking-grid">
                <div class="booking-form">

                    <section class="card">
                        <label class="form-label" for="inputDate">Pilih Tanggal Bermain</label>
                        <input type="date" id="inputDate" class="input-field"
                               value="{{ date('Y-m-d') }}"
                               min="{{ date('Y-m-d') }}">
                        <div id="dayBanner" class="day-type-banner weekday" style="margin-top:1rem;">
                            <span id="dayBannerIcon">📅</span>
                            <span id="dayBannerText">Memuat info hari...</span>
                            <span id="dayBannerPrice" style="margin-left:auto;font-size:1rem;"></span>
                        </div>
                    </section>

                    <section class="card">
                        <label class="form-label" for="inputTime">Pilih Jam Mulai Bermain</label>
                        <div class="time-input-wrap">
                            <input type="time" id="inputTime" min="10:00" max="21:55" step="300">
                        </div>
                        <p class="time-hint">Operasional 10:00 – 22:00 WIB. Input dibulatkan ke kelipatan 5 menit.</p>
                        <p class="time-error" id="timeError">Jam harus antara 10:00 – 21:55 WIB.</p>
                    </section>

                    <section class="card">
                        <label class="form-label">Jumlah Kart (Maks. 5)</label>
                        <div class="kart-counter">
                            <button type="button" id="btnMinus" onclick="changeKart(-1)" disabled>−</button>
                            <div class="kart-count-display" id="kartDisplay">1</div>
                            <button type="button" id="btnPlus" onclick="changeKart(1)">+</button>
                            <span style="font-size:0.9rem;color:var(--text-gray);margin-left:4px;" id="kartLabel">kart dipilih</span>
                        </div>
                        <div class="kart-dots" id="kartDots"></div>
                    </section>

                    <section class="card" id="openMatchSection">
                        <label class="form-label">🏁 Open Match</label>
                        <p style="font-size:0.85rem;color:var(--text-gray);margin-bottom:0.8rem;">Buka sesi untuk pemain lain yang ingin bergabung bermain bersama Anda</p>
                        
                        <div class="open-match-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleOpenMatch" onchange="toggleOpen(this.checked)">
                                <span class="toggle-slider"></span>
                            </label>
                            <span id="toggleLabel" style="font-weight:600;color:var(--text-gray);">Match Tertutup (Private)</span>
                        </div>

                        <div id="openMatchOptions" style="display:none;margin-top:1rem;">
                            <div style="margin-bottom:1rem;">
                                <label class="form-label" style="font-size:0.88rem;">Slot Tambahan untuk Pemain Lain</label>
                                <div class="kart-counter">
                                    <button type="button" id="btnSlotMinus" onclick="changeSlot(-1)" disabled>−</button>
                                    <div class="kart-count-display" id="slotDisplay">1</div>
                                    <button type="button" id="btnSlotPlus" onclick="changeSlot(1)">+</button>
                                    <span style="font-size:0.85rem;color:var(--text-gray);margin-left:4px;" id="slotInfo">slot tersedia (maks. <span id="slotMax">4</span>)</span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label" style="font-size:0.88rem;">Catatan (Opsional)</label>
                                <input type="text" id="inputCatatanOpen" class="input-field" 
                                       placeholder="Misal: Yuk main bareng santai!" maxlength="255"
                                       oninput="document.getElementById('hiddenCatatanOpen').value=this.value">
                            </div>
                        </div>
                    </section>

                </div>

                <aside>
                    <div class="card summary-card">
                        <h3>Ringkasan Booking</h3>
                        <hr>
                        <div class="summary-item"><span>Tanggal</span><strong id="sumDate">-</strong></div>
                        <div class="summary-item"><span>Jam</span><strong id="sumTime">-</strong></div>
                        <div class="summary-item"><span>Jenis Hari</span><strong id="sumDay">-</strong></div>
                        <div class="summary-item"><span>Jumlah Kart</span><strong id="sumKart">1 kart</strong></div>

                        <div class="price-breakdown" id="priceBreakdown">
                            <div class="pb-row">
                                <span>Harga per kart</span>
                                <span id="pbHargaSatuan">Rp -</span>
                            </div>
                            <div class="pb-row">
                                <span id="pbKartLabel">× 1 kart</span>
                                <span></span>
                            </div>
                            <div class="pb-row total">
                                <span>Total</span>
                                <span id="pbTotal">Rp 0</span>
                            </div>
                        </div>

                        <div class="total-section" style="margin-top:1rem;">
                            <p>Total Harga</p>
                            <h2 id="sumTotal">Rp 0</h2>
                        </div>
                        <button type="submit" class="confirm-btn" id="confirmBtn" disabled>Konfirmasi Booking</button>
                        <p id="validationMsg" style="font-size:0.8rem;color:#dc2626;text-align:center;margin-top:0.5rem;display:none;">Lengkapi tanggal dan jam terlebih dahulu.</p>
                    </div>
                </aside>
            </div>
        </form>
    </main>
</div>

<script>
const HARGA_WEEKDAY = {{ (int)$harga_weekday }};
const HARGA_WEEKEND = {{ (int)$harga_weekend }};

let state = {
    tanggal: '{{ date('Y-m-d') }}',
    jam: '',
    jumlahKart: 1,
    isWeekend: false,
    hargaSatuan: 0,
    paketId: 0,
    isOpenMatch: false,
    slotOpen: 1,
};

function formatRupiah(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

function getDayType(dateStr) {
    if (!dateStr) return null;
    const d = new Date(dateStr + 'T00:00:00');
    const day = d.getDay();
    return (day === 0 || day === 6) ? 'weekend' : 'weekday';
}

function getDayName(dateStr) {
    if (!dateStr) return '-';
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const d = new Date(dateStr + 'T00:00:00');
    return days[d.getDay()];
}

function updateDayBanner() {
    const banner = document.getElementById('dayBanner');
    const icon = document.getElementById('dayBannerIcon');
    const text = document.getElementById('dayBannerText');
    const price = document.getElementById('dayBannerPrice');

    if (!state.tanggal) {
        banner.className = 'day-type-banner none';
        text.textContent = 'Pilih tanggal terlebih dahulu';
        price.textContent = '';
        return;
    }

    const type = getDayType(state.tanggal);
    state.isWeekend = (type === 'weekend');
    state.hargaSatuan = state.isWeekend ? HARGA_WEEKEND : HARGA_WEEKDAY;

    if (state.isWeekend) {
        banner.className = 'day-type-banner weekend';
        text.textContent = getDayName(state.tanggal) + ' — Tarif Weekend berlaku';
        price.textContent = formatRupiah(HARGA_WEEKEND) + '/kart';
    } else {
        banner.className = 'day-type-banner weekday';
        icon.textContent = '📅';
        text.textContent = getDayName(state.tanggal) + ' — Tarif Weekday berlaku';
        price.textContent = formatRupiah(HARGA_WEEKDAY) + '/kart';
    }

    fetchPaketId(type);
}

function fetchPaketId(type) {
    fetch('{{ route("booking.paket_id") }}?type=' + type)
        .then(r => r.json())
        .then(id => { state.paketId = parseInt(id) || 0; updateSummary(); })
        .catch(() => { state.paketId = 0; updateSummary(); });
}

function renderKartDots() {
    const wrap = document.getElementById('kartDots');
    wrap.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const dot = document.createElement('div');
        dot.className = 'kart-dot' + (i <= state.jumlahKart ? ' active' : '');
        dot.textContent = '🏎';
        dot.style.cursor = 'pointer';
        dot.onclick = () => { state.jumlahKart = i; updateKart(); };
        wrap.appendChild(dot);
    }
}

function updateKart() {
    document.getElementById('kartDisplay').textContent = state.jumlahKart;
    document.getElementById('btnMinus').disabled = state.jumlahKart <= 1;
    document.getElementById('btnPlus').disabled = state.jumlahKart >= 5;
    document.getElementById('hiddenJumlahOrang').value = state.jumlahKart;
    renderKartDots();
    updateOpenMatchSlots();
    updateSummary();
}

function changeKart(delta) {
    state.jumlahKart = Math.min(5, Math.max(1, state.jumlahKart + delta));
    updateKart();
}

function snapToFive(timeStr) {
    if (!timeStr) return '';
    const [h, m] = timeStr.split(':').map(Number);
    const total = h * 60 + m;
    const snapped = Math.round(total / 5) * 5;
    const sh = Math.floor(snapped / 60);
    const sm = snapped % 60;
    return String(sh).padStart(2,'0') + ':' + String(sm).padStart(2,'0');
}

function validateTime(timeStr) {
    if (!timeStr) return false;
    const [h, m] = timeStr.split(':').map(Number);
    const total = h * 60 + m;
    return total >= 600 && total <= 1315;
}

function updateSummary() {
    const total = state.hargaSatuan * state.jumlahKart;

    document.getElementById('sumDate').textContent = state.tanggal || '-';
    document.getElementById('sumTime').textContent = state.jam || '-';
    document.getElementById('sumDay').textContent = state.tanggal ? (state.isWeekend ? 'Weekend' : 'Weekday') : '-';
    document.getElementById('sumKart').textContent = state.jumlahKart + ' kart';
    document.getElementById('pbHargaSatuan').textContent = formatRupiah(state.hargaSatuan);
    document.getElementById('pbKartLabel').textContent = '× ' + state.jumlahKart + ' kart';
    document.getElementById('pbTotal').textContent = formatRupiah(total);
    document.getElementById('sumTotal').textContent = formatRupiah(total);

    document.getElementById('hiddenTanggal').value = state.tanggal;
    document.getElementById('hiddenJam').value = state.jam;
    document.getElementById('hiddenTotalHarga').value = total;
    document.getElementById('hiddenPaketId').value = state.paketId;

    const ready = state.tanggal && state.jam && validateTime(state.jam) && state.paketId > 0;
    document.getElementById('confirmBtn').disabled = !ready;
    document.getElementById('validationMsg').style.display = ready ? 'none' : 'block';
}

document.getElementById('inputDate').addEventListener('change', function() {
    state.tanggal = this.value;
    updateDayBanner();
});

document.getElementById('inputTime').addEventListener('change', function() {
    let val = snapToFive(this.value);
    this.value = val;
    state.jam = val;
    const err = document.getElementById('timeError');
    if (val && !validateTime(val)) {
        err.style.display = 'block';
        state.jam = '';
    } else {
        err.style.display = 'none';
    }
    updateSummary();
});

document.getElementById('formBooking').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!state.jam || !validateTime(state.jam)) {
        document.getElementById('timeError').style.display = 'block';
        return;
    }

    const btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('tanggal_booking', state.tanggal);
    formData.append('jam_booking', state.jam);
    formData.append('paket_id', state.paketId);
    formData.append('jumlah_orang', state.jumlahKart);
    formData.append('total_harga', state.hargaSatuan * state.jumlahKart);
    formData.append('is_open_match', state.isOpenMatch ? 1 : 0);
    formData.append('maks_slot_open', state.isOpenMatch ? state.slotOpen : 0);
    formData.append('catatan_open_match', document.getElementById('inputCatatanOpen').value);

    fetch('{{ route("booking.proses") }}', { method: 'POST', body: formData })
        .then(r => r.text())
        .then(result => {
            const id = result.trim().replace(/\D/g, '');
            if (id !== '') {
                alert('🎉 Booking Berhasil! Silakan lakukan pembayaran.');
                window.location.href = '#';
            } else {
                alert('Gagal: ' + result);
                btn.disabled = false;
                btn.textContent = 'Konfirmasi Booking';
            }
        })
        .catch(() => {
            alert('Koneksi gagal. Coba lagi.');
            btn.disabled = false;
            btn.textContent = 'Konfirmasi Booking';
        });
});
p
function toggleOpen(checked) {
    state.isOpenMatch = checked;
    document.getElementById('openMatchOptions').style.display = checked ? 'block' : 'none';
    document.getElementById('toggleLabel').textContent = checked ? '🟢 Match Terbuka (Open)' : 'Match Tertutup (Private)';
    document.getElementById('toggleLabel').style.color = checked ? 'var(--primary)' : 'var(--text-gray)';
    document.getElementById('hiddenOpenMatch').value = checked ? 1 : 0;
    if (checked) updateOpenMatchSlots();
}

function updateOpenMatchSlots() {
    const maxSlot = 5 - state.jumlahKart;
    document.getElementById('slotMax').textContent = maxSlot;
    if (state.slotOpen > maxSlot) state.slotOpen = Math.max(1, maxSlot);
    if (maxSlot <= 0) {
        state.isOpenMatch = false;
        document.getElementById('toggleOpenMatch').checked = false;
        document.getElementById('openMatchOptions').style.display = 'none';
        document.getElementById('toggleLabel').textContent = 'Tidak tersedia (5 kart penuh)';
        document.getElementById('toggleLabel').style.color = '#dc2626';
        document.getElementById('hiddenOpenMatch').value = 0;
        return;
    }
    document.getElementById('slotDisplay').textContent = state.slotOpen;
    document.getElementById('btnSlotMinus').disabled = state.slotOpen <= 1;
    document.getElementById('btnSlotPlus').disabled = state.slotOpen >= maxSlot;
    document.getElementById('hiddenMaksSlot').value = state.slotOpen;
}

function changeSlot(delta) {
    const maxSlot = 5 - state.jumlahKart;
    state.slotOpen = Math.min(maxSlot, Math.max(1, state.slotOpen + delta));
    updateOpenMatchSlots();
}

updateDayBanner();
updateKart();
</script>
</body>
</html>