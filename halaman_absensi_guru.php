<?php
session_start();
require_once '../config/db.php';

// Cek login guru
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit();
}

// Ambil data guru
$id_user = $_SESSION['id_user'];
$sql_guru = "SELECT * FROM guru WHERE id_user = '$id_user'";
$guru = $pdo->query($sql_guru)->fetch();

// Proses simpan absensi
if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $id_kelas = $_POST['id_kelas'];
    
    foreach ($_POST['status'] as $id_siswa => $status) {
        $catatan = $_POST['catatan'][$id_siswa] ?? '';
        
        // Cek apakah sudah ada absensi
        $cek = $pdo->query("SELECT id_absen FROM absensi WHERE id_siswa='$id_siswa' AND tanggal='$tanggal'")->fetch();
        
        if ($cek) {
            // Update
            $pdo->query("UPDATE absensi SET status='$status', catatan='$catatan', input_by='{$guru['id_guru']}' WHERE id_absen='{$cek['id_absen']}'");
        } else {
            // Insert baru
            $id_absen = 'ABS' . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT);
            $pdo->query("INSERT INTO absensi VALUES ('$id_absen', '$id_siswa', '$tanggal', '$status', '$catatan', '{$guru['id_guru']}')");
        }
    }
    $success = "Absensi berhasil disimpan!";
}

// Ambil daftar kelas
$kelas = $pdo->query("SELECT * FROM kelas WHERE id_guru='{$guru['id_guru']}'")->fetchAll();

// Ambil data siswa jika kelas dipilih
$selected_kelas = $_GET['kelas'] ?? '';
$selected_tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$siswa = [];
$absensi_exists = [];

if ($selected_kelas) {
    $siswa = $pdo->query("SELECT * FROM siswa WHERE id_kelas='$selected_kelas' AND status='Aktif' ORDER BY nama_siswa")->fetchAll();
    
    // Ambil absensi yang sudah ada
    foreach ($siswa as $s) {
        $abs = $pdo->query("SELECT * FROM absensi WHERE id_siswa='{$s['id_siswa']}' AND tanggal='$selected_tanggal'")->fetch();
        if ($abs) {
            $absensi_exists[$s['id_siswa']] = $abs;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Absensi Harian</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; background: #f1f5f9; }
        .sidebar {
            width: 260px; background: #1e293b; color: white; padding: 30px 20px; height: 100vh; position: fixed;
        }
        .sidebar h2 { color: #38bdf8; margin-bottom: 40px; font-size: 20px; }
        .sidebar a {
            display: block; color: #cbd5e1; text-decoration: none; padding: 12px 16px; border-radius: 10px; margin-bottom: 8px;
        }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: white; }
        .main-content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #0f172a; }
        .card {
            background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .filter-form {
            display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;
        }
        .filter-item {
            flex: 1; min-width: 200px;
        }
        .filter-item label {
            display: block; margin-bottom: 8px; font-weight: 600; color: #334155;
        }
        .filter-item select, .filter-item input {
            width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px;
        }
        .btn {
            background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 12px; 
            font-weight: 600; cursor: pointer; height: fit-content; align-self: flex-end;
        }
        .btn:hover { background: #1d4ed8; }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        th {
            background: #f8fafc; padding: 16px; text-align: left; border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 16px; border-bottom: 1px solid #e2e8f0;
        }
        .status-select {
            padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100px;
        }
        .catatan-input {
            padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%;
        }
        .btn-simpan {
            background: #10b981; color: white; padding: 16px; border: none; border-radius: 12px;
            font-weight: 700; font-size: 16px; cursor: pointer; width: 100%; margin-top: 30px;
        }
        .alert {
            padding: 16px; background: #dcfce7; color: #166534; border-radius: 12px; margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SIS TKIT</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="pengumuman.php">Pengumuman</a>
        <a href="absensi.php" class="active">📋 Absensi</a>
        <a href="../logout.php" style="margin-top: 50px; color: #f87171;">Keluar</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>📋 Absensi Harian</h1>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert"><?= $success ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="GET" class="filter-form">
                <div class="filter-item">
                    <label>Pilih Kelas</label>
                    <select name="kelas" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id_kelas'] ?>" <?= $selected_kelas == $k['id_kelas'] ? 'selected' : '' ?>>
                                <?= $k['nama_kelas'] ?> - <?= $k['tingkat'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?= $selected_tanggal ?>" required>
                </div>
                <button type="submit" class="btn">Tampilkan</button>
            </form>

            <?php if ($selected_kelas && !empty($siswa)): ?>
                <form method="POST">
                    <input type="hidden" name="tanggal" value="<?= $selected_tanggal ?>">
                    <input type="hidden" name="id_kelas" value="<?= $selected_kelas ?>">
                    
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($siswa as $s): 
                                $abs = $absensi_exists[$s['id_siswa']] ?? null;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $s['nama_siswa'] ?></strong></td>
                                <td><?= $s['nis'] ?></td>
                                <td>
                                    <select name="status[<?= $s['id_siswa'] ?>]" class="status-select">
                                        <option value="Hadir" <?= ($abs['status']??'')=='Hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="Sakit" <?= ($abs['status']??'')=='Sakit' ? 'selected' : '' ?>>Sakit</option>
                                        <option value="Izin" <?= ($abs['status']??'')=='Izin' ? 'selected' : '' ?>>Izin</option>
                                        <option value="Alpha" <?= ($abs['status']??'')=='Alpha' ? 'selected' : '' ?>>Alpha</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="catatan[<?= $s['id_siswa'] ?>]" 
                                           class="catatan-input" value="<?= htmlspecialchars($abs['catatan']??'') ?>"
                                           placeholder="Catatan (opsional)">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" name="simpan" class="btn-simpan">💾 Simpan Absensi</button>
                </form>
            <?php elseif ($selected_kelas): ?>
                <p style="text-align: center; padding: 50px; color: #64748b;">Tidak ada siswa di kelas ini</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>