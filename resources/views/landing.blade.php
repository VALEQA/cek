<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go-Kart Racing Hub - Full Throttle</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .price-ww-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 700px;
            margin: 0 auto;
        }
        .price-ww-card {
            background: linear-gradient(145deg, #111827, #1f2937);
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .price-ww-card:hover {
            transform: translateY(-5px);
        }
        .price-ww-card.weekday-card { border-top: 4px solid #38bdf8; }
        .price-ww-card.weekend-card { border-top: 4px solid #f59e0b; }
        .price-ww-card h3 {
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            font-size: 1.2rem;
            margin: 0.5rem 0 0.2rem;
            text-transform: uppercase;
        }
        .price-ww-card .desc { color: #9ca3af; font-size: 0.85rem; margin-bottom: 1.5rem; }
        .price-ww-card .cost { margin: 1.5rem 0; }
        .price-ww-card .cost .currency { font-size: 0.9rem; color: #10b981; font-family: 'Orbitron', sans-serif; vertical-align: super; }
        .price-ww-card .cost .amount { font-size: 2rem; font-weight: bold; color: #10b981; font-family: 'Orbitron', sans-serif; }
        .price-ww-card .cost .unit { font-size: 0.75rem; color: #9ca3af; display: block; margin-top: 0.2rem; }
        .day-chips { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; margin-top: 1rem; }
        .day-chip { padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; }
        .chip-weekday { background: #1e3a5f; color: #38bdf8; }
        .chip-weekend { background: #4a3000; color: #f59e0b; }
        .per-kart-note { font-size: 0.78rem; color: #6b7280; margin-top: 0.8rem; }

        #rpmCurveCanvas {
            width: 100%;
            height: 200px;
            display: block;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-top: 20px;
            background: #0a0a0a;
        }
        .rpm-legend { display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap; }
        .rpm-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-color-muted); }
        .rpm-legend-dot { width: 12px; height: 3px; border-radius: 2px; }
    </style>
</head>
<body>
    <div class="hero">
        <h2 class="main-subtitle">GO-KART RACING HUB</h2>
        <h1 class="main-title">FULL THROTTLE</h1>
        <p class="hero-desc">Layout trek, spesifikasi mesin, rekam jejak putaran, sinyal bendera, dan detail fasilitas — semuanya di satu tempat.</p>
        
        <div class="hero-cta-container">
            <a href="{{ route('login') }}" class="hero-login-btn">🏁 BOOK YOUR RACE - START LOGIN</a>
        </div>

        <div class="scroll-down" id="scroll-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 13l5 5 5-5M7 6l5 5 5-5"/></svg>
        </div>
    </div>

    <div id="specs" class="panel">
        <h2 class="panel-header">Engine Specifications</h2>
        <p class="panel-desc">Kekuatan pendorong mesin-mesin di trek</p>
        
        <div class="engine-specs">
            <div class="spec-column-left">
                <div class="spec-item"><span class="spec-label">Tipe Mesin</span><span class="spec-value orange">4-Stroke, Single Cylinder</span></div>
                <div class="spec-item"><span class="spec-label">Tenaga Maksimum</span><span class="spec-value">6.5 HP @ 3,600 RPM</span></div>
                <div class="spec-item"><span class="spec-label">Sistem Bahan Bakar</span><span class="spec-value">Karburetor</span></div>
                <div class="spec-item"><span class="spec-label">Transmisi</span><span class="spec-value orange">Kopling Sentrifugal, Chain Drive</span></div>
            </div>
            <div class="spec-column-right">
                <div class="spec-item"><span class="spec-label">Kapasitas</span><span class="spec-value">200cc</span></div>
                <div class="spec-item"><span class="spec-label">Torsi</span><span class="spec-value">12 Nm @ 2,500 RPM</span></div>
                <div class="spec-item"><span class="spec-label">Sistem Pendingin</span><span class="spec-value">Berpendingin Udara</span></div>
                <div class="spec-item"><span class="spec-label">Kecepatan Tertinggi</span><span class="spec-value">~70 km/jam</span></div>
            </div>
        </div>

        <h3 class="curve-title">Kurva Tenaga & Torsi</h3>
        <canvas id="rpmCurveCanvas"></canvas>
        <div class="rpm-legend">
            <div class="rpm-legend-item"><div class="rpm-legend-dot" style="background:#ff5722;"></div> Tenaga (HP)</div>
            <div class="rpm-legend-item"><div class="rpm-legend-dot" style="background:#38bdf8;"></div> Torsi (Nm)</div>
        </div>

        <div class="power-curve-placeholder" style="display:none;">
            <span class="pcp-rpm r1">1,000 RPM</span>
            <span class="pcp-rpm r2">2,500</span>
            <span class="pcp-rpm r3">4,000 RPM</span>
        </div>
    </div>

    <div class="price-container-panel">
        <h2 class="panel-header" style="text-align:center;font-family:'Orbitron',sans-serif;color:#fff;">Tarif Bermain</h2>
        <p class="panel-desc" style="text-align:center;color:#9ca3af;margin-bottom:3.5rem;">Harga per kart, berlaku untuk semua durasi sesi — otomatis terdeteksi saat booking</p>
        
        <div class="price-ww-grid">
            <div class="price-ww-card weekday-card">
                <span class="package-badge" style="background:#1e3a5f;color:#38bdf8;">WEEKDAY</span>
                <h3>Hari Kerja</h3>
                <p class="desc">Tarif spesial untuk bermain di hari Senin hingga Jumat</p>
                <div class="cost">
                    <span class="currency">Rp</span>
                    <span class="amount">{{ number_format($harga_weekday, 0, ',', '.') }}</span>
                    <span class="unit">per kart / sesi</span>
                </div>
                <div class="day-chips">
                    <span class="day-chip chip-weekday">Senin</span>
                    <span class="day-chip chip-weekday">Selasa</span>
                    <span class="day-chip chip-weekday">Rabu</span>
                    <span class="day-chip chip-weekday">Kamis</span>
                    <span class="day-chip chip-weekday">Jumat</span>
                </div>
                <p class="per-kart-note">Maks. 5 kart per booking</p>
            </div>

            <div class="price-ww-card weekend-card">
                <span class="package-badge" style="background:#4a3000;color:#f59e0b;">WEEKEND</span>
                <h3>Akhir Pekan</h3>
                <p class="desc">Tarif akhir pekan untuk bermain di Sabtu dan Minggu</p>
                <div class="cost">
                    <span class="currency">Rp</span>
                    <span class="amount">{{ number_format($harga_weekend, 0, ',', '.') }}</span>
                    <span class="unit">per kart / sesi</span>
                </div>
                <div class="day-chips">
                    <span class="day-chip chip-weekend">Sabtu</span>
                    <span class="day-chip chip-weekend">Minggu</span>
                </div>
                <p class="per-kart-note">Maks. 5 kart per booking</p>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2 class="panel-header">Trek Balap</h2>
        <p class="panel-desc">Menjelajahi liku-liku sirkuit</p>

        <div class="race-track-map">
            <svg id="track-svg" viewBox="0 0 1000 500" preserveAspectRatio="none">
                <path d="M 400,450 L 150,450 C 100,450 100,350 100,300 L 100,100 C 100,50 300,50 400,50" stroke="#4a148c" stroke-width="40" fill="none" />
                <path d="M 400,50 L 900,50 C 950,50 950,150 950,200 C 950,250 900,250 900,300" stroke="#1b5e20" stroke-width="40" fill="none" />
                <path d="M 900,300 C 900,350 850,350 800,350 L 500,350 C 450,350 450,450 400,450" stroke="#f57f17" stroke-width="40" fill="none" />
                <path d="M 100,100 C 100,50 300,50 300,50 L 900,50 C 950,50 950,150 950,200 C 950,250 900,250 900,300 C 900,350 850,350 800,350 L 500,350 C 450,350 450,450 400,450 L 150,450 C 100,450 100,350 100,300 Z" stroke="#d32f2f" stroke-width="5" stroke-dasharray="15, 10" fill="none" />
                <g id="car-group">
                    <image href="{{ asset('kart.png') }}" x="-30" y="-35" width="60" height="60" />
                    <animateMotion dur="6s" repeatCount="indefinite" path="M 100,100 C 100,50 300,50 300,50 L 900,50 C 950,50 950,150 950,200 C 950,250 900,250 900,300 C 900,350 850,350 800,350 L 500,350 C 450,350 450,450 400,450 L 150,450 C 100,450 100,350 100,300 Z" rotate="auto" />
                </g>
                <g id="start-finish-group">
                    <text x="400" y="15" fill="#ffffff" font-family="'Orbitron', sans-serif" font-size="16" text-anchor="middle" font-weight="bold">START / FINISH</text>
                    <line x1="400" y1="20" x2="400" y2="78" stroke="#ffffff" stroke-width="9" />
                </g>
            </svg>
            <div class="turn-label" style="top:15%;right:7%;">T1</div>
            <div class="turn-label" style="top:55%;right:7%;">T2</div>
            <div class="turn-label" style="bottom:25%;left:48%;">T3</div>
            <div class="turn-label" style="bottom:5%;left:38%;">T4</div>
            <div class="turn-label" style="bottom:5%;left:12%;">T5</div>
            <div class="turn-label" style="top:15%;left:8%;">T6</div>
        </div>

        <div class="track-stats">
            <div class="stat-box"><div class="stat-value">200 m</div><div class="stat-label">Panjang</div></div>
            <div class="stat-box"><div class="stat-value">6</div><div class="stat-label">Tikungan</div></div>
            <div class="stat-box"><div class="stat-value">3</div><div class="stat-label">Lurus</div></div>
        </div>
    </div>

    <div class="panel">
        <h2 class="panel-header">Waktu Tercepat Tercatat</h2>
        <p class="panel-desc">Rekor putaran yang belum terkalahkan</p>
        <div class="lap-record-label">LAP RECORD</div>
        @if($has_record)
            @php $split_time = explode('.', $record_time); @endphp
            <div class="lap-record">{{ $split_time[0] }}<span class="red">.{{ $split_time[1] }}</span></div>
            <div class="lap-record-sub">seconds</div>
            <div class="record-driver-info">Set by <strong>{{ $driver_name }}</strong></div>
        @else
            <div class="lap-record">15<span class="red">.351</span></div>
            <div class="lap-record-sub">seconds</div>
            <div class="record-driver-info">Set by Driver #7 — Maret 2025 (Default)</div>
        @endif
        <div class="sector-times">
            <div class="sector-box green"><div class="sector-title"><span class="dot"></span>SECTOR 1</div><div class="sector-time">{{ $sector_1 }}s</div></div>
            <div class="sector-box yellow"><div class="sector-title"><span class="dot"></span>SECTOR 2</div><div class="sector-time">{{ $sector_2 }}s</div></div>
            <div class="sector-box purple"><div class="sector-title"><span class="dot"></span>SECTOR 3</div><div class="sector-time">{{ $sector_3 }}s</div></div>
        </div>
        <div class="sector-breakdown-label">Sector Breakdown</div>
        <div class="sector-breakdown-bar">
            <div class="sb-segment green"></div>
            <div class="sb-segment yellow"></div>
            <div class="sb-segment purple"></div>
        </div>
    </div>

    <div class="panel">
        <h2 class="panel-header">Sinyal Bendera</h2>
        <p class="panel-desc">Memahami sinyal yang menjaga keselamatan Anda di trek</p>
        <div class="flags-grid">
            <div class="flag-box green"><div class="flag-header"><div class="flag-color green-bg"></div><h4 class="flag-name">Bendera Hijau</h4></div><p class="flag-desc">Trek bersih — balapan sedang berlangsung. Kecepatan penuh ke depan.</p></div>
            <div class="flag-box yellow"><div class="flag-header"><div class="flag-color yellow-bg"></div><h4 class="flag-name">Bendera Kuning</h4></div><p class="flag-desc">Awas di trek. Perlambat, tidak diperbolehkan menyalip.</p></div>
            <div class="flag-box red"><div class="flag-header"><div class="flag-color red-bg"></div><h4 class="flag-name">Bendera Merah</h4></div><p class="flag-desc">Sesi segera dihentikan. Semua kart harus berhenti.</p></div>
            <div class="flag-box white"><div class="flag-header"><div class="flag-color checkered-bg"></div><h4 class="flag-name">Bendera Kotak</h4></div><p class="flag-desc">Balapan berakhir. Sesi atau balapan telah selesai.</p></div>
            <div class="flag-box blue"><div class="flag-header"><div class="flag-color blue-bg"></div><h4 class="flag-name">Bendera Biru</h4></div><p class="flag-desc">Kart yang lebih cepat mendekat. Biarkan mereka lewat dengan aman.</p></div>
            <div class="flag-box black"><div class="flag-header"><div class="flag-color white-bg"></div><h4 class="flag-name">Bendera Hitam</h4></div><p class="flag-desc">Driver diskualifikasi atau harus kembali ke pit.</p></div>
        </div>
    </div>

    <div class="panel">
        <h2 class="panel-header">Fasilitas</h2>
        <p class="panel-desc">Segala yang Anda butuhkan untuk hari balapan yang hebat</p>
        <div class="facilities-grid">
            <div class="facility-box"><div class="facility-icon-container">🛋️</div><div><h4 class="facility-title">Kursi Penonton</h4><p class="facility-desc">Tribun tertutup dengan visibilitas trek penuh untuk hingga 200 tamu.</p></div></div>
            <div class="facility-box"><div class="facility-icon-container">🔧</div><div><h4 class="facility-title">Area Pit Stop</h4><p class="facility-desc">Teluk yang dilengkapi untuk layanan kart, ganti ban, dan perbaikan cepat.</p></div></div>
            <div class="facility-box"><div class="facility-icon-container">🛋️</div><div><h4 class="facility-title">Lounge Driver</h4><p class="facility-desc">Lounge ber-AC dengan monitor yang menampilkan waktu putaran langsung.</p></div></div>
            <div class="facility-box"><div class="facility-icon-container">☕</div><div><h4 class="facility-title">Kafe & Segaran</h4><p class="facility-desc">Menyajikan minuman panas dan dingin, makanan ringan, dan hidangan.</p></div></div>
            <div class="facility-box"><div class="facility-icon-container">🛡️</div><div><h4 class="facility-title">Ruang Briefing</h4><p class="facility-desc">Ruang khusus untuk orientasi keselamatan pra-balapan wajib.</p></div></div>
            <div class="facility-box"><div class="facility-icon-container">👷</div><div><h4 class="facility-title">Sewa Gear & Helm</h4><p class="facility-desc">Helm, setelan, dan sarung tangan tersedia dalam semua ukuran.</p></div></div>
        </div>
    </div>

    <footer>© 2026 Go-Kart Racing Hub. All rights reserved.</footer>

    <script src="{{ asset('script.js') }}"></script>
    <script>
    function initRpmCurve() {
        const canvas = document.getElementById('rpmCurveCanvas');
        if (!canvas) return;

        const dpr = window.devicePixelRatio || 1;
        const W = canvas.getBoundingClientRect().width || canvas.offsetWidth || 600;
        const H = 200;
        canvas.width = Math.round(W * dpr);
        canvas.height = Math.round(H * dpr);
        canvas.style.width = W + 'px';
        canvas.style.height = H + 'px';

        const ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);

        const data = [
            [1000, 1.2,  8.5],
            [1200, 1.5,  8.9],
            [1500, 1.9,  9.4],
            [1800, 2.4,  9.8],
            [2000, 2.8, 10.3],
            [2200, 3.3, 10.9],
            [2500, 4.0, 12.0],
            [2800, 4.5, 11.8],
            [3000, 4.9, 11.5],
            [3200, 5.4, 11.1],
            [3400, 5.9, 10.8],
            [3600, 6.5, 10.2],
            [3800, 6.2,  9.6],
            [4000, 5.8,  8.9],
        ];

        const PAD = { top: 24, right: 24, bottom: 36, left: 44 };
        const PW = W - PAD.left - PAD.right;
        const PH = H - PAD.top - PAD.bottom;

        const minRPM = 1000, maxRPM = 4000;
        const maxHP  = 8;
        const maxTq  = 14;

        function xPos(rpm) { return PAD.left + ((rpm - minRPM) / (maxRPM - minRPM)) * PW; }
        function yHP(hp)   { return PAD.top  + PH - (hp  / maxHP)  * PH; }
        function yTq(tq)   { return PAD.top  + PH - (tq  / maxTq)  * PH; }

        let progress = 0;
        const DURATION = 90;

        function drawGrid() {
            ctx.strokeStyle = '#2a2a2a';
            ctx.lineWidth = 0.5;
            for (let i = 0; i <= 4; i++) {
                const y = PAD.top + (PH / 4) * i;
                ctx.beginPath();
                ctx.moveTo(PAD.left, y);
                ctx.lineTo(PAD.left + PW, y);
                ctx.stroke();
            }

            const rpmTicks = [1000, 1500, 2000, 2500, 3000, 3500, 4000];
            ctx.fillStyle = '#666';
            ctx.font = '10px Roboto, sans-serif';
            ctx.textAlign = 'center';
            rpmTicks.forEach(rpm => {
                const x = xPos(rpm);
                ctx.beginPath();
                ctx.moveTo(x, PAD.top);
                ctx.lineTo(x, PAD.top + PH);
                ctx.stroke();
                ctx.fillText((rpm / 1000).toFixed(1) + 'k', x, PAD.top + PH + 14);
            });

            ctx.fillStyle = '#555';
            ctx.fillText('RPM', PAD.left + PW / 2, PAD.top + PH + 30);

            ctx.save();
            ctx.translate(12, PAD.top + PH / 2);
            ctx.rotate(-Math.PI / 2);
            ctx.fillStyle = '#ff5722';
            ctx.textAlign = 'center';
            ctx.fillText('HP', 0, 0);
            ctx.restore();

            ctx.save();
            ctx.translate(W - 8, PAD.top + PH / 2);
            ctx.rotate(Math.PI / 2);
            ctx.fillStyle = '#38bdf8';
            ctx.textAlign = 'center';
            ctx.fillText('Nm', 0, 0);
            ctx.restore();
        }

        function drawCurve(colorStroke, valueIndex, yFn, upTo) {
            const total = data.length;
            const floatLimit = upTo * (total - 1);
            const limit = Math.floor(floatLimit);
            if (limit < 1) return;

            ctx.beginPath();
            ctx.strokeStyle = colorStroke;
            ctx.lineWidth = 2.5;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';

            for (let i = 0; i <= limit; i++) {
                const x = xPos(data[i][0]);
                const y = yFn(data[i][valueIndex]);
                if (i === 0) ctx.moveTo(x, y);
                else         ctx.lineTo(x, y);
            }

            if (limit < total - 1) {
                const frac = floatLimit - limit;
                const x0 = xPos(data[limit][0]),     y0 = yFn(data[limit][valueIndex]);
                const x1 = xPos(data[limit+1][0]),   y1 = yFn(data[limit+1][valueIndex]);
                ctx.lineTo(x0 + (x1 - x0) * frac, y0 + (y1 - y0) * frac);
            }
            ctx.stroke();

            const tipIdx = Math.min(limit, total - 1);
            const tipFrac = (limit < total - 1) ? (floatLimit - limit) : 0;
            const tx = xPos(data[tipIdx][0]) + (limit < total-1 ? (xPos(data[tipIdx+1][0]) - xPos(data[tipIdx][0])) * tipFrac : 0);
            const ty = yFn(data[tipIdx][valueIndex]) + (limit < total-1 ? (yFn(data[tipIdx+1][valueIndex]) - yFn(data[tipIdx][valueIndex])) * tipFrac : 0);
            ctx.beginPath();
            ctx.arc(tx, ty, 4, 0, Math.PI * 2);
            ctx.fillStyle = colorStroke;
            ctx.fill();
        }

        function drawPeakLabels() {
            ctx.font = '10px Roboto, sans-serif';
            ctx.textAlign = 'center';

            ctx.fillStyle = '#38bdf8';
            ctx.fillText('Peak Torsi', xPos(2500), yTq(12.0) - 10);

            ctx.fillStyle = '#ff5722';
            ctx.fillText('Peak Power', xPos(3600), yHP(6.5) - 10);
        }

        function drawFrame() {
            ctx.clearRect(0, 0, W, H);
            drawGrid();

            const t = Math.min(progress / DURATION, 1);
            const eased = 1 - Math.pow(1 - t, 3);

            drawCurve('#ff5722', 1, yHP, eased);
            drawCurve('#38bdf8', 2, yTq, eased);

            if (eased >= 1) drawPeakLabels();

            progress++;
            if (progress <= DURATION + 5) requestAnimationFrame(drawFrame);
        }

        const obs = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) { obs.disconnect(); drawFrame(); }
        }, { threshold: 0.2 });
        obs.observe(canvas);
    }

    if (document.readyState === 'complete') {
        initRpmCurve();
    } else {
        window.addEventListener('load', initRpmCurve);
    }
    </script>
</body>
</html>