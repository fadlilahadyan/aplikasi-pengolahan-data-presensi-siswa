<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['username'];
$ortu = $pdo->query("SELECT * FROM orang_tua WHERE email='$email'")->fetch();

if (!$ortu) {
    $id_ortu = 'ORT' . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);
    $pdo->query("INSERT INTO orang_tua (id_ortu, email) VALUES ('$id_ortu', '$email')");
    $ortu['id_ortu'] = $id_ortu;
}

// Ambil data anak
$anak = $pdo->query("SELECT s.*, k.nama_kelas FROM siswa s 
                     LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                     WHERE s.id_ortu='{$ortu['id_ortu']}' AND s.status='Aktif'")->fetchAll();

// Ambil pengumuman terbaru
$pengumuman = $pdo->query("SELECT p.*, u.nama_lengkap FROM pengumuman p 
                           JOIN users u ON p.id_user = u.id_user 
                           ORDER BY p.tanggal DESC LIMIT 3")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Orang Tua</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .navbar {
            background: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px;
        }
        .grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;
        }
        .card {
            background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .card h3 { margin-bottom: 20px; color: #1e293b; }
        .anak-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 0; border-bottom: 1px solid #e2e8f0;
        }
        .anak-item:last-child { border-bottom: none; }
        .anak-nama { font-weight: 600; }
        .anak-kelas { font-size: 14px; color: #64748b; }
        .btn {
            background: #2563eb; color: white; padding: 8px 16px; border-radius: 8px;
            text-decoration: none; font-size: 14px;
        }
        .pengumuman-item {
            padding: 15px 0; border-bottom: 1px solid #e2e8f0;
        }
        .pengumuman-item:last-child { border-bottom: none; }
        .pengumuman-judul { font-weight: 600; margin-bottom: 5px; }
        .pengumuman-meta { font-size: 12px; color: #64748b; }
        .logout {
            color: #ef4444; text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>SIS TKIT - Orang Tua</h2>
        <a href="../logout.php" class="logout">Keluar</a>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h1>Halo, <?= $_SESSION['nama_lengkap'] ?>! 👋</h1>
            <p>Pantau perkembangan putra/putri Anda di TKIT Fathurrobbany</p>
        </div>

        <div class="grid">
            <!-- Data Anak -->
            <div class="card">
                <h3>👶 Data Anak</h3>
                <?php if (empty($anak)): ?>
                    <p style="color: #94a3b8; text-align: center; padding: 20px;">
                        Belum ada data anak. Hubungi admin sekolah.
                    </p>
                <?php else: ?>
                    <?php foreach ($anak as $a): ?>
                    <div class="anak-item">
                        <div>
                            <div class="anak-nama"><?= $a['nama_siswa'] ?></div>
                            <div class="anak-kelas"><?= $a['nama_kelas'] ?> - NIS: <?= $a['nis'] ?></div>
                        </div>
                        <a href="absensi-anak.php?siswa=<?= $a['id_siswa'] ?>" class="btn">Lihat Absensi</a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pengumuman Terbaru -->
            <div class="card">
                <h3>📢 Pengumuman</h3>
                <?php if (empty($pengumuman)): ?>
                    <p style="color: #94a3b8; text-align: center; padding: 20px;">
                        Belum ada pengumuman
                    </p>
                <?php else: ?>
                    <?php foreach ($pengumuman as $p): ?>
                    <div class="pengumuman-item">
                        <div class="pengumuman-judul"><?= $p['judul'] ?></div>
                        <div class="pengumuman-meta">
                            <?= date('d M Y', strtotime($p['tanggal'])) ?> • oleh <?= $p['nama_lengkap'] ?>
                        </div>
                        <p style="margin-top: 8px; color: #475569;"><?= substr($p['isi'], 0, 100) ?>...</p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>