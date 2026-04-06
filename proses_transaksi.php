<?php
session_start();
include "1koneksi.php";

// Pastikan ada cart
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    echo "Keranjang kosong!";
    exit;
}

// Ambil metode pembayaran
$metode = $_POST['metode'];
$pin = ($metode === 'non_tunai') ? $_POST['pin'] : NULL;

// Hitung total
$totalQty = 0;
$totalHarga = 0;

foreach($_SESSION['cart'] as $item){
    $totalQty += $item['qty'];
    $totalHarga += $item['subtotal'];
}

// Simpan ke tabel transaksi
$stmt = $connection->prepare("INSERT INTO transaksi (total_qty, total_harga, metode, pin) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $totalQty, $totalHarga, $metode, $pin);
$stmt->execute();

$id_transaksi = $stmt->insert_id;

// Simpan detail tiap barang
$stmt_detail = $connection->prepare("INSERT INTO transaksi_detail (id_transaksi, kode_barang, nama_barang, qty, harga, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

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
    $stmt_detail->execute();
}

// Hapus cart setelah transaksi sukses
unset($_SESSION['cart']);

// Redirect ke halaman sukses
header("Location: pembayaran_berhasil.php?id=$id_transaksi");
exit;
?>