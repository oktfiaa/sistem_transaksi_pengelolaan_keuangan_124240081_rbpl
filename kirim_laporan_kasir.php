<?php
session_start();
include "1koneksi.php";

$tanggal = date('Y-m-d');
$nama_kasir = $_SESSION['nama'];

// HITUNG TRANSAKSI HARI INI
$query = mysqli_query($connection, 
    "SELECT 
        SUM(total_harga) as omzet, 
        COUNT(id_transaksi) as total_transaksi 
     FROM transaksi 
     WHERE DATE(tanggal) = '$tanggal'"
);

$data = mysqli_fetch_assoc($query);

$omzet = $data['omzet'] ?? 0;
$total_transaksi = $data['total_transaksi'] ?? 0;


// 🔍 CEK SUDAH ADA LAPORAN ATAU BELUM
$cek = mysqli_query($connection, 
    "SELECT * FROM laporan_harian 
     WHERE tanggal='$tanggal' 
     AND nama_kasir='$nama_kasir'"
);

$ada = mysqli_fetch_assoc($cek);

if($ada){

    // ❗ OPTIONAL: kalau sudah ACC, tidak boleh diubah
    if($ada['status'] == 'acc'){
        echo "<script>
                alert('Laporan sudah disetujui, tidak bisa diubah!');
                window.location='rekap.php';
              </script>";
        exit;
    }

    // 🔁 UPDATE DATA
    mysqli_query($connection, "
        UPDATE laporan_harian 
        SET 
            omzet='$omzet',
            total_transaksi='$total_transaksi',
            status='menunggu'
        WHERE tanggal='$tanggal' 
        AND nama_kasir='$nama_kasir'
    ");

    echo "<script>
            alert('Laporan berhasil diperbarui dan dikirim ulang!');
            window.location='rekap.php';
          </script>";

} else {

    // 🆕 INSERT DATA BARU
    mysqli_query($connection, 
        "INSERT INTO laporan_harian 
        (tanggal, nama_kasir, omzet, total_transaksi, status) 
        VALUES 
        ('$tanggal','$nama_kasir','$omzet','$total_transaksi','menunggu')"
    );

    echo "<script>
            alert('Laporan berhasil dikirim ke admin!');
            window.location='rekap.php';
          </script>";
}
?>