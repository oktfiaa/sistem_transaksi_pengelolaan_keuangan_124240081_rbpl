<?php
session_start();
include "1koneksi.php";

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    echo "Keranjang kosong!";
    exit;
}

$metode = $_POST['metode'];
$pin = ($metode === 'non_tunai') ? $_POST['pin'] : NULL;

$totalQty = 0;
$totalHarga = 0;

foreach($_SESSION['cart'] as $item){
    $totalQty += $item['qty'];
    $totalHarga += $item['subtotal'];
}

$tanggal = date('Y-m-d H:i:s');

// CEK KONEKSI
if(!$connection){
    die("Koneksi gagal: " . mysqli_connect_error());
}

// SIMPAN TRANSAKSI
$stmt = $connection->prepare("INSERT INTO transaksi (total_qty, total_harga, metode, pin, tanggal) VALUES (?, ?, ?, ?, ?)");

if(!$stmt){
    die("Prepare gagal: " . $connection->error);
}

$stmt->bind_param("iisss", $totalQty, $totalHarga, $metode, $pin, $tanggal);

if(!$stmt->execute()){
    die("Execute gagal: " . $stmt->error);
}

$id_transaksi = $stmt->insert_id;

// SIMPAN DETAIL
$stmt_detail = $connection->prepare("INSERT INTO transaksi_detail (id_transaksi, kode_barang, nama_barang, qty, harga, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

if(!$stmt_detail){
    die("Prepare detail gagal: " . $connection->error);
}

foreach($_SESSION['cart'] as $item){
    $stmt_detail->bind_param(
        "issidd",
        $id_transaksi,
        $item['kode'],
        $item['nama'],
        $item['qty'],
        $item['harga'],
        $item['subtotal']
    );

    if(!$stmt_detail->execute()){
        die("Execute detail gagal: " . $stmt_detail->error);
    }
}

unset($_SESSION['cart']);

header("Location: pembayaran_berhasil.php?id=$id_transaksi");
exit;
?>