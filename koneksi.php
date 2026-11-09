<?php
$host = "localhost";        // Di hosting/XAMPP biasanya tetap 'localhost'
$user = "root";             // Ganti dengan username database lo (default XAMPP: root)
$pass = "";                 // Ganti dengan password database lo (default XAMPP: kosong)
$db   = "jokiin_db";        // Nama database MySQL yang lo buat

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>