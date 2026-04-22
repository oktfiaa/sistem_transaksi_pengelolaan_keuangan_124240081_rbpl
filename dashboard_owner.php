<?php
session_start();
include "1koneksi.php";

// FILTER PERIODE
$filter = $_GET['filter'] ?? '7hari';

$where = "";
if($filter == '7hari'){
    $where = "WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}elseif($filter == '30hari'){
    $where = "WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

// AMBIL DATA (HANYA ACC)
$query = mysqli_query($connection,"
    SELECT * FROM laporan_harian 
    $where AND status='acc'
    ORDER BY tanggal ASC
");

// TOTAL OMZET
$total_omzet = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(omzet) as total FROM laporan_harian 
    $where AND status='acc'
"))['total'] ?? 0;

// TOTAL TRANSAKSI
$total_transaksi = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(total_transaksi) as total FROM laporan_harian 
    $where AND status='acc'
"))['total'] ?? 0;

// RATA-RATA
$rata = 0;
$jumlah_hari = mysqli_num_rows($query);
if($jumlah_hari > 0){
    $rata = $total_omzet / $jumlah_hari;
}

// HARI TERLARIS
$top = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT tanggal, omzet FROM laporan_harian
    $where AND status='acc'
    ORDER BY omzet DESC LIMIT 1
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Owner</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family:'Poppins', sans-serif;
    margin:0;
    background:#f5f7fb;
}

/* HEADER */
.header{
    background:#fff;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
}

.title{
    font-size:22px;
    font-weight:600;
}

.menu{
    display:flex;
    gap:10px;
}

.menu a{
    text-decoration:none;
    padding:8px 12px;
    border-radius:8px;
    font-size:13px;
    background:#e5e7eb;
    color:black;
}

/* CONTAINER */
.container{
    padding:20px;
}

/* FILTER */
.filter{
    margin-bottom:15px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.filter a{
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    background:#ddd;
    color:black;
    font-size:13px;
}

.active{
    background:#2F00FF !important;
    color:white !important;
}

/* CARDS */
.cards{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:200px;
    padding:15px;
    border-radius:10px;
    color:white;
}

.blue{ background:#4f46e5; }
.green{ background:#1BB628; }
.orange{ background:#FF8D28; }

/* TABLE */
.table-box{
    margin-top:20px;
    background:white;
    padding:15px;
    border-radius:10px;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:10px;
    border-bottom:1px solid #ddd;
    font-size:14px;
}

/* MOBILE */
@media(max-width:600px){
    .title{
        font-size:16px;
    }
    th, td{
        font-size:12px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="title">Selamat Datang Owner</div>
    <div class="menu">
        <a href="index.php">Kembali</a>
        <a href="5logout.php">Logout</a>
    </div>
</div>

<div class="container">

<h2>Dashboard Omzet</h2>

<!-- FILTER -->
<div class="filter">
    <a href="?filter=7hari" class="<?= $filter=='7hari'?'active':'' ?>">7 Hari</a>
    <a href="?filter=30hari" class="<?= $filter=='30hari'?'active':'' ?>">30 Hari</a>
    <a href="?filter=semua" class="<?= $filter=='semua'?'active':'' ?>">Semua</a>

    <a href="export_excell.php?filter=<?= $filter ?>" style="background:#8AFFBF;">
        Download Excel
    </a>
</div>

<!-- CARDS -->
<div class="cards">
    <div class="card blue">
        <h3>Total Omzet</h3>
        <h2>Rp <?= number_format($total_omzet) ?></h2>
    </div>

    <div class="card green">
        <h3>Total Transaksi</h3>
        <h2><?= $total_transaksi ?></h2>
    </div>

    <div class="card orange">
        <h3>Rata-rata / Hari</h3>
        <h2>Rp <?= number_format($rata) ?></h2>
    </div>
</div>

<!-- TOP SALES -->
<?php if($top){ ?>
<div class="table-box">
    <h3>⭐ Penjualan Tertinggi</h3>
    <p>
        Tanggal: <b><?= $top['tanggal'] ?></b> |
        Omzet: <b>Rp <?= number_format($top['omzet']) ?></b>
    </p>
</div>
<?php } ?>

<!-- TABLE -->
<div class="table-box">
    <h3>Data Omzet</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Omzet</th>
            <th>Transaksi</th>
        </tr>

        <?php $no=1; mysqli_data_seek($query,0); while($row=mysqli_fetch_assoc($query)){ ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['nama_kasir'] ?></td>
            <td>Rp <?= number_format($row['omzet']) ?></td>
            <td><?= $row['total_transaksi'] ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</div>

</body>
</html>