<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Absensi</title>
</head>
<body>

<h2>Data Absensi Siswa</h2>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Nama Siswa</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Catatan</th>
</tr>

<?php
$data = mysqli_query($koneksi, "
SELECT absensi.*, siswa.nama_siswa 
FROM absensi 
JOIN siswa ON absensi.id_siswa = siswa.id_siswa
");

while ($d = mysqli_fetch_array($data)) {
?>
<tr>
    <td><?php echo $d['id_absen']; ?></td>
    <td><?php echo $d['nama_siswa']; ?></td>
    <td><?php echo $d['tanggal']; ?></td>
    <td><?php echo $d['status']; ?></td>
    <td><?php echo $d['catatan']; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="absensi.php">Kembali ke Form</a>

</body>
</html>
