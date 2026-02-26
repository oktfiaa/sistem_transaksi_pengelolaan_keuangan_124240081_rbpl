<?php
session_start();
include "1koneksi.php";

if(isset($_POST['simpan'])){

    $id_kasir = $_SESSION['user_id'];
    $metode   = $_POST['metode_bayar'];
    $tanggal  = date("Y-m-d H:i:s");

    $total = 0;

    foreach($_POST['nominal'] as $n){
        $total += $n;
    }

    mysqli_query($connection,
        "INSERT INTO transaksi 
        (tanggal, total, metode_bayar, id_kasir) 
        VALUES 
        ('$tanggal', '$total', '$metode', '$id_kasir')"
    );

    echo "Transaksi berhasil disimpan! Total: Rp " . number_format($total);
}
?>