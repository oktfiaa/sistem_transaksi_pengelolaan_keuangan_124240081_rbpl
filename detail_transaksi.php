<?php
include "1koneksi.php";

$id = $_GET['id'] ?? 0;

// Ambil data transaksi
$trx = mysqli_fetch_assoc(mysqli_query($connection,
    "SELECT * FROM transaksi WHERE id_transaksi ='$id'"
));

// Ambil detail barang
$detail = mysqli_query($connection,
    "SELECT * FROM transaksi_detail WHERE id_transaksi='$id'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:#f5f5f5;
}

/* HEADER */
.header{
    background:#F4F4F4;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.title{
    font-size:24px;
    font-weight:600;
}

.btn-header{
    background:#D9D9D9;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
    color:black;
}

/* CONTAINER */
.container{
    padding:20px;
    display:flex;
    justify-content:center;
}

/* CARD */
.card{
    background:#e0e7ff;
    width:100%;
    max-width:800px;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

/* TITLE */
.card h2{
    text-align:center;
    margin-bottom:5px;
}

.trx-id{
    text-align:center;
    margin-bottom:20px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th, td{
    padding:10px;
    border-bottom:1px solid #ccc;
    text-align:left;
    font-size:14px;
}

/* FOOTER BOX */
.summary{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
    gap:10px;
    flex-wrap:wrap;
}

.box{
    flex:1;
    background:white;
    padding:15px;
    border-radius:10px;
    text-align:center;
}

/* BUTTON */
.btn-back{
    display:block;
    margin:20px auto 0;
    background:#2F00FF;
    color:white;
    padding:12px;
    border-radius:10px;
    text-decoration:none;
    width:200px;
    text-align:center;
}

/* MOBILE */
@media(max-width:600px){
    th, td{
        font-size:12px;
        padding:8px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="title">Detail Transaksi</div>
    <a href="rekap.php" class="btn-header">Kembali</a>
</div>

<div class="container">
    <div class="card">

        <h2>Detail Transaksi</h2>
        <div class="trx-id">
            TRX-<?php echo $trx['id_transaksi']; ?>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>

            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($detail)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nama_barang']; ?></td>
                <td><?php echo $row['qty']; ?></td>
                <td>Rp <?php echo number_format($row['harga']); ?></td>
                <td>Rp <?php echo number_format($row['subtotal']); ?></td>
            </tr>
            <?php } ?>
        </table>

        <div class="summary">
            <div class="box">
                <div>Total Harga</div>
                <strong>Rp <?php echo number_format($trx['total_harga']); ?></strong>
            </div>

            <div class="box">
                <div>Metode</div>
                <strong><?php echo ucfirst($trx['metode']); ?></strong>
            </div>
        </div>

        <a href="rekap.php" class="btn-back">Kembali</a>

    </div>
</div>

</body>
</html>