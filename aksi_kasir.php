<?php
include "1koneksi.php";

$id = $_GET['id'];

if(isset($_POST['simpan'])){
    $aksi = $_POST['aksi'];

    mysqli_query($connection,"
        UPDATE laporan_harian 
        SET aksi_kasir='$aksi'
        WHERE id_laporan='$id'
    ");

    echo "<script>alert('Berhasil dikirim'); location='notifikasi_kasir.php';</script>";
}
?>

<form method="POST" style="padding:20px;">
    <h3>Tindaklanjuti</h3>
    <textarea name="aksi" placeholder="Contoh: Sudah nombok selisih 20rb" style="width:100%; height:100px;"></textarea>
    <br><br>
    <button name="simpan">Kirim</button>
</form>