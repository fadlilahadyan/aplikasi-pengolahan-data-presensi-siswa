<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'orang_tua') {
    header("Location: ../login.php");
    exit();
}

// Ambil data orang tua
$email = $_SESSION['username'];
$ortu = $pdo->query("SELECT * FROM orang_tua WHERE email='$email'")->fetch();

if (!$ortu) {
    die("Data orang tua tidak ditemukan");
}

// Ambil data anak
$anak = $pdo->query("SELECT s.*, k.nama_kelas FROM siswa s 
                     LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                     WHERE s.id_ortu='{$ortu['id_ortu']}' AND s.status='Aktif'")->fetchAll();

$id_siswa = $_GET['siswa'] ?? ($anak[0]['id_siswa'] ?? '');
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$absensi = [];
if ($id_siswa) {
    $absensi = $pdo->query("SELECT * FROM absensi WHERE id_siswa='$id_siswa' 
                            AND YEAR(tanggal)='$tahun' AND MONTH(tanggal)='$bulan' 
                            ORDER BY tanggal DESC")->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Absensi Anak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .navbar {
            background: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .card {
            background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .student-selector {
            display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;
        }
        .student-btn {
            padding: 15px 30px; background: white; border: 2px solid #e2e8f0; border-radius: 12px;
            cursor: pointer; font-weight: 600; text-decoration: none; color: #334155;
        }
        .student-btn.active {
            background: #2563eb; border-color: #2563eb; color: white;
        }
        .filter-form {
            display: flex; gap: 20px; margin: 20px 0; align-items: flex-end;
        }
        .filter-item {
            flex: 1;
        }
        .filter-item label {
            display: block; margin-bottom: 8px; font-weight: 600;
        }
        .filter-item select {
            width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;
        }
        .btn {
            background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px;
            cursor: pointer;
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        th {
            background: #f8fafc; padding: 12px; text-align: left;
        }
        td {
            padding: 12px; border-bottom: 1px solid #e2e8f0;
        }
        .badge {
            padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-sakit { background: #fff3cd; color: #856404; }
        .badge-izin { background: #cff4fc; color: #055160; }
        .badge-alpha { background: #f8d7da; color: #721c24; }
        .stats {
            display: grid; grid-template-columns: repeat(4,1fr); gap: 15px; margin: 20px 0;
        }
        .stat-item {
            background: #f8fafc; padding: 15px; border-radius: 12px; text-align: center;
        }
        .stat-item h3 { font-size: 14px; color: #64748b; }
        .stat-item .number { font-size: 24px; font-weight: 700; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>SIS TKIT</h2>
        <a href="dashboard.php" style="color: #2563eb; text-decoration: none;">← Kembali</a>
    </div>

    <div class="container">
        <h1 style="margin-bottom: 20px;">📊 Absensi Anak</h1>

        <!-- Pilih Anak -->
        <div class="student-selector">
            <?php foreach ($anak as $a): ?>
                <a href="?siswa=<?= $a['id_siswa'] ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" 
                   class="student-btn <?= $id_siswa == $a['id_siswa'] ? 'active' : '' ?>">
                    <?= $a['nama_siswa'] ?> (<?= $a['nama_kelas'] ?>)
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($id_siswa): ?>
            <div class="card">
                <form method="GET" class="filter-form">
                    <input type="hidden" name="siswa" value="<?= $id_siswa ?>">
                    <div class="filter-item">
                        <label>Bulan</label>
                        <select name="bulan">
                            <?php for($i=1; $i<=12; $i++): ?>
                                <option value="<?= str_pad($i,2,'0',STR_PAD_LEFT) ?>" <?= $bulan==str_pad($i,2,'0',STR_PAD_LEFT)?'selected':'' ?>>
                                    <?= date('F', mktime(0,0,0,$i,1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Tahun</label>
                        <select name="tahun">
                            <?php for($i=date('Y')-1; $i<=date('Y')+1; $i++): ?>
                                <option value="<?= $i ?>" <?= $tahun==$i?'selected':'' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn">Tampilkan</button>
                </form>

                <?php 
                // Hitung statistik
                $hadir = $sakit = $izin = $alpha = 0;
                foreach ($absensi as $a) {
                    switch($a['status']) {
                        case 'Hadir': $hadir++; break;
                        case 'Sakit': $sakit++; break;
                        case 'Izin': $izin++; break;
                        case 'Alpha': $alpha++; break;
                    }
                }
                ?>

                <!-- Statistik -->
                <div class="stats">
                    <div class="stat-item">
                        <h3>Hadir</h3>
                        <div class="number" style="color: #10b981;"><?= $hadir ?></div>
                    </div>
                    <div class="stat-item">
                        <h3>Sakit</h3>
                        <div class="number" style="color: #f59e0b;"><?= $sakit ?></div>
                    </div>
                    <div class="stat-item">
                        <h3>Izin</h3>
                        <div class="number" style="color: #3b82f6;"><?= $izin ?></div>
                    </div>
                    <div class="stat-item">
                        <h3>Alpha</h3>
                        <div class="number" style="color: #ef4444;"><?= $alpha ?></div>
                    </div>
                </div>

                <!-- Tabel Absensi -->
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Diinput Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absensi as $a): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($a['status']) ?>">
                                    <?= $a['status'] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($a['catatan'] ?: '-') ?></td>
                            <td>
                                <?php 
                                $guru = $pdo->query("SELECT nama_guru FROM guru WHERE id_guru='{$a['input_by']}'")->fetch();
                                echo $guru ? $guru['nama_guru'] : 'Guru';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($absensi)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                                Belum ada data absensi untuk bulan ini
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>