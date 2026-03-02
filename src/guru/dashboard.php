<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru - Premium</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap" style="color: var(--accent);"></i> TKIT FATHUROBANI
        </div>
        
        <div class="nav-group">
            <span class="nav-label">Utama</span>
            <a href="dashboard.php" class="nav-item active"><i class="fas fa-th-large"></i> Dashboard</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Kelas</span>
            <a href="absensi.php" class="nav-item"><i class="fas fa-calendar-check"></i> Absensi Siswa</a>
            <a href="laporan.php" class="nav-item"><i class="fas fa-chart-line"></i> Laporan Perkembangan</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Keuangan</span>
            <a href="keuangan.php" class="nav-item"><i class="fas fa-wallet"></i> Informasi Keuangan</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Pemberitahuan</span>
            <a href="pengumuman.php" class="nav-item"><i class="fas fa-bullhorn"></i> Pengumuman</a>
        </div>

        <div style="margin-top: auto;">
            <a href="../logout.php" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <p>Welcome to the School Management System</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Siswa Kelas</div>
                <div class="value">24</div>
            </div>
            <div class="stat-card">
                <div class="label">Hadir Hari Ini</div>
                <div class="value">22</div>
            </div>
            <div class="stat-card">
                <div class="label">Belum Bayar SPP</div>
                <div class="value">5</div>
            </div>
            <div class="stat-card">
                <div class="label">Tahun Ajaran</div>
                <div class="value">2024-2025</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="content-card">
                <div class="card-header">Aktivitas Terbaru</div>
                <ul style="list-style: none; font-size: 14px;">
                    <li style="margin-bottom: 15px; display: flex; gap: 10px;">
                        <span style="color: #2563eb;">●</span>
                        <div><strong>Absensi Selesai:</strong> Kelas B1 telah diabsen.<br><small style="color: var(--text-muted);">2 jam yang lalu</small></div>
                    </li>
                </ul>
            </div>

            <div class="content-card">
                <div class="card-header">Aksi Cepat</div>
                <button class="action-btn btn-blue"><i class="fas fa-user-check"></i> Mulai Absensi</button>
                <a href="buat-pengumuman.php" style="text-decoration: none;"><button class="action-btn btn-green"><i class="fas fa-plus"></i> Buat Pengumuman</button></a>
                <button class="action-btn btn-purple"><i class="fas fa-file-alt"></i> Input Perkembangan</button>
            </div>
        </div>
    </div>
</body>
</html>