<?php
session_start();
include "1koneksi.php";

$today = date('Y-m-d');

// SUMMARY
$summary = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT 
        SUM(total_harga) as omzet,
        COUNT(*) as total_transaksi
    FROM transaksi
    WHERE DATE(tanggal) = '$today'
"));

// DATA TABEL
$data = mysqli_query($connection,"
    SELECT * FROM transaksi
    WHERE DATE(tanggal) = '$today'
    ORDER BY id_transaksi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekap Harian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body{
            margin:0;
            font-family:'Poppins', sans-serif;
            background:#f5f5f5;
        }

        .header{
            background:#F4F4F4;
            padding:20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0px 4px 4px rgba(0,0,0,0.2);
            flex-wrap:wrap;
        }

        .title{
            font-size:24px;
            font-weight:600;
        }

        .date{
            font-size:14px;
            color:#555;
        }

        .container{
            padding:20px;
        }

        .cards{
            display:flex;
            gap:20px;
            flex-wrap:wrap;
        }

        .card{
            flex:1;
            min-width:250px;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 4px 10px rgba(0,0,0,0.1);
        }

        .omzet{
            background:#eef2ff;
            color:#2F00FF;
        }

        .trx{
            background:#8AFFBF;
            color:#1BB628;
        }

        .card h2{
            margin:5px 0;
        }

        .table-box{
            margin-top:20px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 4px 10px rgba(0,0,0,0.1);
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            padding:12px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }

        th{
            background:#f0f0f0;
        }

        a{
            color:#2F00FF;
            text-decoration:underline;
        }

        .btn{
            margin-top:20px;
            padding:15px;
            border:none;
            border-radius:10px;
            width:100%;
            cursor:pointer;
            font-weight:600;
        }

        .btn-kirim{
            background:#1BB628;
            color:white;
        }

        .btn-simpan{
            background:#ddd;
        }

        .btn-header {
            background:#D9D9D9;
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
            color:black;
            font-size:14px;
            display:inline-block;
        }

        .btn-header:hover {
            background:#cfcfcf;
        }

        .header-right {
            display:flex;
            gap:10px;
        }

        @media(max-width:768px){
            .title{
                font-size:20px;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <div>
        <div class="title">Rekap Harian Kasir</div>
        <div class="date"><?php echo date('d M Y'); ?></div>
    </div>

    <div class="header-right">
        <a href="transaksi.php" class="btn-header">Input Transaksi</a>
        <a href="notifikasi_kasir.php" class="btn-header">Notifikasi Verifikasi</a>
        <a href="4dashboard_kasir.php" class="btn-header">Kembali</a>
    </div>
</div>

<div class="container">

    <!-- CARD -->
    <div class="cards">
        <div class="card omzet">
            <div>Total Omzet Hari Ini</div>
            <h2>Rp <?php echo number_format($summary['omzet'] ?? 0,0,',','.'); ?></h2>
        </div>

        <div class="card trx">
            <div>Jumlah Transaksi</div>
            <h2><?php echo $summary['total_transaksi'] ?? 0; ?></h2>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-box">
        <h3>Detail Transaksi Harian</h3>

        <table>
            <tr>
                <th>No</th>
                <th>No Transaksi</th>
                <th>Metode</th>
                <th>Subtotal</th>
                <th>Detail</th>
            </tr>

            <?php 
            $no=1;
            if(mysqli_num_rows($data) > 0){
                while($row = mysqli_fetch_assoc($data)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>TRX-<?php echo $row['id_transaksi']; ?></td>
                <td><?php echo $row['metode']; ?></td>
                <td>Rp <?php echo number_format($row['total_harga'],0,',','.'); ?></td>
                <td>
                    <a href="detail_transaksi.php?id=<?php echo $row['id_transaksi']; ?>">
                        Klik
                    </a>
                </td>
            </tr>
            <?php 
                }
            } else {
            ?>
            <tr>
                <td colspan="5" style="text-align:center;color:#888;">
                    Belum ada transaksi hari ini
                </td>
            </tr>
            <?php } ?>
        </table>

        <div class="btn-group">

            <!-- KIRIM KE ADMIN -->
            <form method="POST" action="kirim_laporan_kasir.php" style="flex:1;">
                <button type="submit" class="btn btn-kirim">
                    Kirim Laporan ke Admin
                </button>
            </form>

            <!-- SIMPAN SEMENTARA -->
            <button type="button" class="btn btn-simpan" onclick="simpanSementara()">
                Simpan Rekap Sementara
            </button>

        </div>

    </div>

</div>

<script>
function simpanSementara(){
    alert("Rekap berhasil disimpan sementara!");
}
</script>

</body>
</html>