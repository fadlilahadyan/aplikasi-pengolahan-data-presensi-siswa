<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_rbpl_tkit_fr");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
