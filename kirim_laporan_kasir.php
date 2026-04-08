<?php
session_start();
include "1koneksi.php";

$tanggal = date('Y-m-d');
$nama_kasir = $_SESSION['nama'];

// hitung dari transaksi hari ini
$query = mysqli_query($connection, 
    "SELECT SUM(total) as omzet, COUNT(*) as total_transaksi 
     FROM transaksi 
     WHERE DATE(tanggal) = '$tanggal'"
);

$data = mysqli_fetch_assoc($query);

$omzet = $data['omzet'] ?? 0;
$total_transaksi = $data['total_transaksi'] ?? 0;

// simpan ke laporan_harian
mysqli_query($connection, 
    "INSERT INTO laporan_harian (tanggal, nama_kasir, omzet, total_transaksi) 
     VALUES ('$tanggal','$nama_kasir','$omzet','$total_transaksi')"
);

header("Location: rekap.php");