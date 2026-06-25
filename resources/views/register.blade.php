
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Go-Kart Racing Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="panel" style="max-width: 500px; margin: 50px auto;">
            <h2 class="panel-header" style="text-align: center;">🏁 RACER REGISTRATION</h2>
            <p class="panel-desc" style="text-align: center;">Daftarkan dirimu untuk mendapatkan Super License</p>
            
            @if(session('error'))
    <p style='color: #d32f2f; text-align: center;'>{{ session('error') }}</p>
@endif

            <form action="" method="POST" class="license-form">
                @csrf
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required autocomplete="off">
                <input type="number" name="nomor_hp" placeholder="Nomor HP (Contoh: 0812...)" required>
                <input type="email" name="email" placeholder="Alamat Email" required>
                <input type="password" name="password" placeholder="Password Baru" required>
                
                <button type="submit" name="daftar" style="margin-top: 10px;">START ENGINE (DAFTAR)</button>
            </form>
            
            <p style="text-align: center; color: #aaa; font-size: 14px; margin-top: 20px;">
                Sudah punya lisensi? <a href="{{ route('login') }}">Sudah punya lisensi? Login</a>
        </div>
    </div>
</body>
</html>