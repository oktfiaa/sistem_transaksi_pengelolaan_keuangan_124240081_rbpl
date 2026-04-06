<?php
session_start();
include "1koneksi.php";

// TAMBAH BARANG
if(isset($_POST['tambah'])){
    $kode = $_POST['kode_barang'];
    $qty  = $_POST['qty'];

    $query = mysqli_query($connection, 
        "SELECT * FROM barang WHERE kode_barang='$kode'"
    );

    $barang = mysqli_fetch_assoc($query);

    if($barang){
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        $item = [
            'nama' => $barang['nama_barang'],
            'harga' => $barang['harga'],
            'qty' => $qty,
            'subtotal' => $barang['harga'] * $qty
        ];

        $_SESSION['cart'][] = $item;
    }
}

// HITUNG TOTAL
$total = 0;
$totalQty = 0;
if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $total += $item['subtotal'];
        $totalQty += $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Kasir</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #F4F4F4;
            padding: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .left, .right {
            background: white;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h3 {
            margin-top: 0;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background: #3a00ff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .summary {
            background: #eef2ff;
        }

        .total {
            font-size: 20px;
            font-weight: 600;
        }

        .btn-bayar {
            background: #16a34a;
            width: 100%;
            margin-top: 20px;
        }

        /* MOBILE */
        @media(max-width: 768px){
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="header">
    Kasir - Sistem Manajemen Keuangan
</div>

<div class="container">

    <!-- LEFT: INPUT + CART -->
    <div class="left">

        <h3>Input Barang</h3>

        <form method="POST">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" required>

            <label>Jumlah</label>
            <input type="number" name="qty" required>

            <button type="submit" name="tambah">+ Tambah ke Keranjang</button>
        </form>

        <hr>

        <h3>Keranjang</h3>

        <table>
            <tr>
                <th>Nama</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>

            <?php
            if(isset($_SESSION['cart'])){
                foreach($_SESSION['cart'] as $item){
                    echo "<tr>
                        <td>{$item['nama']}</td>
                        <td>{$item['qty']}</td>
                        <td>Rp {$item['harga']}</td>
                        <td>Rp {$item['subtotal']}</td>
                    </tr>";
                }
            }
            ?>

        </table>

    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="right summary">

        <h3>Ringkasan Pembayaran</h3>

        <p>Jumlah Item: <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></p>
        <p>Total Qty: <?php echo $totalQty; ?></p>

        <hr>

        <p class="total">Total: Rp <?php echo $total; ?></p>

        <form action="pembayaran.php" method="POST">
            <button class="btn-bayar">Pilih Metode Pembayaran</button>
        </form>

    </div>

</div>

</body>
</html>