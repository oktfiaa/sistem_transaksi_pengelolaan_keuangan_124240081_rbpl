<?php

$hostname = "localhost";
$username = "root";      // default ada di XAMPP
$password = "";          // kosong karena nggak saya setting
$database   = "sistem_transaksi_pengelolaankeuangan";  // nama database

$connection = mysqli_connect($hostname, $username, $password, $database);

if (!$connection) {
    die("Koneksi gagal ;)" . mysqli_connect_error());
}

echo "Koneksi Anda Berhasil!";
?>
