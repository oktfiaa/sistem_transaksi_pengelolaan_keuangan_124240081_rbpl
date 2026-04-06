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
            'kode' => $barang['kode_barang'],
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
            margin:0; 
            font-family:'Poppins', sans-serif; 
            background:#f5f5f5;
        }
        .header {
            background:#F4F4F4;
            padding:20px 40px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0px 4px 4px rgba(0,0,0,0.2);
        }
        .title {
            font-size:28px;
            font-weight:600;
        }
        .header-right {
            display:flex;
            gap:15px;
        }
        .btn-header {
            background:#D9D9D9;
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
            color:black;
            font-size:14px;
            white-space:nowrap;}
        .btn-header:hover {
            background:#cfcfcf;
        }
        .container {
            display:flex;
            gap:20px;
            padding:20px;
        }
        .left,.right {
            background:white;
            padding:20px;
            border-radius:10px;
            flex:1;
            box-shadow:0px 4px 10px rgba(0,0,0,0.1);
        }
        h3 {
            margin-top:0;
        }
        input {
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
        }
        button {
            padding:10px;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }
        button:hover {
            opacity:0.9;
        }
        .btn-tunai {
            background:#16a34a;
            color:white;
            width:100%;
            margin-top:20px;
        }
        table {
            width:100%;
            border-collapse:collapse;
        }
        th,td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:left;}
        .summary {
            background:#eef2ff;
        }
        .total {
            font-size:20px;
            font-weight:600;
        }
        .modal {
            display:none;
            position:fixed;
            z-index:999;
            left:0;
            top:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
        }
        .modal-content {
            background:white;
            padding:20px;
            width:300px;
            margin:100px auto;
            border-radius:10px;
            text-align:center;
        }
        @media(max-width:768px){
            .container{
                flex-direction:column;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">Kasir</div>
    <div class="header-right">
        <a href="notifikasi.php" class="btn-header">Notifikasi Verifikasi</a>
        <a href="rekap.php" class="btn-header">Lihat Rekap Harian</a>
    </div>
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

        <!-- Tombol Tunai -->
        <form method="POST" action="proses_transaksi.php">
            <input type="hidden" name="metode" value="tunai">
            <button type="submit" class="btn-tunai">Bayar Tunai</button>
        </form>

        <!-- Tombol Non-Tunai -->
        <button onclick="openModal()" style="background:#1BB628;color:white;padding:12px;border-radius:10px;cursor:pointer;margin-top:10px;width:100%;">Bayar Non-Tunai</button>
    </div>
</div>

<!-- Modal PIN Non-Tunai -->
<div id="modalPembayaran" class="modal">
    <div class="modal-content">
        <h3>Masukkan PIN</h3>
        <form method="POST" action="proses_transaksi.php">
            <input type="hidden" name="metode" value="non_tunai">
            <input type="password" name="pin" placeholder="PIN" required>
            <button type="submit" style="background:#1BB628;color:white;padding:12px;border-radius:10px;margin-top:10px;">Bayar</button>
        </form>
        <button onclick="closeModal()" style="margin-top:10px;">Batal</button>
    </div>
</div>

<script>
function openModal() {
    document.getElementById("modalPembayaran").style.display = "block";
}
function closeModal() {
    document.getElementById("modalPembayaran").style.display = "none";
}
</script>

</body>
</html>