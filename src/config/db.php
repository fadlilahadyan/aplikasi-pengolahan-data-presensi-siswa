<?php
// File: src/config/db.php

$host = 'localhost';
$dbname = 'db_rbpl_tkit_fr'; // Nama database sesuai yang kamu buat di phpMyAdmin
$username = 'root'; 

$password = ''; 

try {
    // Membuat koneksi menggunakan PDO untuk keamanan maksimal (mencegah SQL Injection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set atribut PDO untuk menampilkan error secara detail (Exception)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buka komentar (hapus tanda //) pada baris echo di bawah ini HANYA JIKA kamu ingin mengetes koneksi
    // echo "Koneksi ke database db_tkit_fathurrobbany BERHASIL bro!";
    
} catch (PDOException $e) {
    // Jika koneksi gagal, sistem akan berhenti (die) dan menampilkan pesan error
    die("KONEKSI GAGAL: " . $e->getMessage());
}
?>