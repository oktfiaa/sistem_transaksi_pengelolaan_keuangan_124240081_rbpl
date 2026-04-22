<?php
include "1koneksi.php";

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if($aksi == 'acc'){
    mysqli_query($connection, "UPDATE laporan_harian SET status='acc' WHERE id_laporan='$id'");
}else{
    mysqli_query($connection, "UPDATE laporan_harian SET status='ditolak' WHERE id_laporan='$id'");
}

header("Location: verifikasi.php");