<?php
$servername = "localhost";
$username = "root";     // Default user XAMPP
$password = "";         // Default password XAMPP (kosong)
$database = "healthylife"; // Nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// echo "Koneksi berhasil"; // Hilangkan komentar ini jika ingin mengetes
?>

