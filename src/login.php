<?php
// ... Kode PHP tetap sama seperti sebelumnya ...
// (Pastikan config/db.php ada, kalau tidak layar akan blank putih lagi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$success_message = '';
if (isset($_GET['status']) && $_GET['status'] == 'sukses') {
    $success_message = "Pendaftaran berhasil! Silakan masuk dengan akun kamu.";
}

require_once 'config/db.php';

// ... (Logika login kamu di sini) ...
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIS TKIT Fathurrobbany</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-left">
        <img src="assets/pramuka.jpg" alt="Kegiatan Siswa" class="background-photo">
        <div class="image-overlay"></div>
        <h1 class="text-hero">
            Permudah interaksi antar <br>
            <span>Guru</span> dan <span>Orang Tua</span> <br>
            secara online!
        </h1>
    </div>

    <div class="login-right">
        <div class="form-container">
            <div class="logo-container">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                SISTEM INFORMASI TKIT 
            </div>

            <h2>Hai, selamat datang</h2>
            <p class="subtitle">Baru di sistem ini?Yuuuu <a href="daftar.php">Daftar Sekarang</a></p>

            <?php if (!empty($error_message)): ?>
                <div class="alert"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="identitas" placeholder="Contoh: email@example.com" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Masukkan kata sandi kamu" required>
                </div>
                <div class="input-group">
                    <select name="role" required>
                        <option value="" disabled selected>-- Pilih Peran Kamu --</option>
                        <option value="guru">Guru</option>
                        <option value="orang_tua">Orang Tua Murid</option>s
                    </select>
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <p class="footer-text">
                Dengan melanjutkan, kamu menerima <a href="#">Syarat Penggunaan</a> dan <br> <a href="#">Kebijakan Privasi</a> sekolah.
            </p>
        </div>
    </div>
</body>
</html>

<?php if (!empty($success_message)): ?>
    <div class="alert" style="background-color: #dcfce7; color: #166534; border-color: #86efac;">
        <?= htmlspecialchars($success_message) ?>
    </div>
<?php endif; ?>