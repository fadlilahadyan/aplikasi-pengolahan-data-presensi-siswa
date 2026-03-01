<?php
include 'koneksi.php';

$id_absen = "ABS" . rand(100,999);
$id_siswa = $_POST['id_siswa'];
$tanggal = $_POST['tanggal'];
$status = $_POST['status'];
$catatan = $_POST['catatan'];

$query = mysqli_query($koneksi, "INSERT INTO absensi 
VALUES ('$id_absen','$id_siswa','$tanggal','$status','$catatan','admin')");

if($query){
    echo "Data berhasil disimpan!";
    echo "<br><a href='absensi.php'>Kembali</a>";
} else {
    echo "Gagal menyimpan data";
}
?>
