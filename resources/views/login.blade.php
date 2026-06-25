
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Go-Kart Racing Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="container">
        <div class="panel" style="max-width: 500px; margin: 100px auto;">
            <h2 class="panel-header" style="text-align: center;">🏁 PIT LANE LOGIN</h2>
            <p class="panel-desc" style="text-align: center;">Masukkan kredensialmu untuk mengakses sirkuit</p>

            @if(session('error'))
    <p style='color: #d32f2f; text-align: center;'>{{ session('error') }}</p>
@endif

            <form action="{{ route('login') }}" method="POST" class="license-form">
                 @csrf
                <input type="email" name="email" class="form-control" placeholder="Masukkan Email Anda" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button type="submit" name="login">ENTER TRACK</button>
            </form>

            <p style="text-align: center; color: #aaa; font-size: 14px; margin-top: 20px;">
                Rookie baru? <a href="{{ route('register') }}" ...>Daftar Lisensi di sini</a>
            </p>
        </div>
    </div>
</body>
</html>