<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Absensi TKIT</title>
</head>
<body>

<h2>Form Absensi Siswa</h2>

<form method="POST" action="simpan_absensi.php">
    
    <label>Nama Siswa:</label>
    <select name="id_siswa" required>
        <option value="">-- Pilih Siswa --</option>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM siswa");
        while ($d = mysqli_fetch_array($data)) {
            echo "<option value='$d[id_siswa]'>$d[nama_siswa]</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required>
    <br><br>

    <label>Status:</label>
    <select name="status" required>
        <option value="Hadir">Hadir</option>
        <option value="Izin">Izin</option>
        <option value="Sakit">Sakit</option>
        <option value="Alpha">Alpha</option>
    </select>
    <br><br>

    <label>Catatan:</label>
    <input type="text" name="catatan">
    <br><br>

    <input type="submit" value="Simpan">

</form>

<hr>
<a href="tampil_absensi.php">Lihat Data Absensi</a>

</body>
</html>
