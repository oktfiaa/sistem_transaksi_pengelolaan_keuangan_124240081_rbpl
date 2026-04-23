<?php
session_start();
include "1koneksi.php";

// FILTER
$filter = $_GET['filter'] ?? '7hari';

$where = "WHERE status='acc'";

if($filter == '7hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}elseif($filter == '30hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

// DATA
$query = mysqli_query($connection,"
    SELECT * FROM laporan_harian 
    $where
    ORDER BY tanggal ASC
");

// TOTAL
$total_omzet = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(omzet) as total FROM laporan_harian $where
"))['total'] ?? 0;

$total_transaksi = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(total_transaksi) as total FROM laporan_harian $where
"))['total'] ?? 0;

// RATA
$rata = 0;
$jumlah = mysqli_num_rows($query);
if($jumlah > 0){
    $rata = $total_omzet / $jumlah;
}

// TOP
$top = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT tanggal, omzet FROM laporan_harian
    $where
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
    padding:18px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
}

.title{
    font-size:22px;
    font-weight:600;
}

.menu a{
    margin-left:10px;
    text-decoration:none;
    padding:10px 14px;
    border-radius:10px;
    background:#e5e7eb;
    color:black;
    font-size:14px;
}

/* CONTAINER */
.container{
    padding:25px;
}

/* FILTER */
.filter{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.filter a{
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    background:#ddd;
    color:black;
    font-size:14px;
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
    margin-bottom:20px;
}

.card{
    flex:1;
    min-width:220px;
    padding:22px;
    border-radius:16px;
    color:white;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.blue{ background:linear-gradient(135deg,#4f46e5,#6366f1); }
.green{ background:linear-gradient(135deg,#16a34a,#22c55e); }
.orange{ background:linear-gradient(135deg,#f97316,#fb923c); }

.card h3{
    margin:0;
    font-size:16px;
    font-weight:500;
}

.card h2{
    margin-top:8px;
    font-size:22px;
}

/* BOX */
.box{
    margin-top:20px;
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

/* LIST DATA */
.list-data{
    display:flex;
    flex-direction:column;
    gap:16px;
    margin-top:15px;
}

.item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#f9fafb;
    padding:20px;
    border-radius:16px;
}

.left{
    display:flex;
    gap:16px;
    align-items:center;
}

.no{
    background:#4f46e5;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
}

.tanggal{
    font-weight:600;
    font-size:16px;
}

.kasir{
    font-size:14px;
    color:#666;
}

.right{
    text-align:right;
}

.omzet{
    font-weight:700;
    font-size:18px;
    color:#16a34a;
}

.trx{
    font-size:13px;
    color:#666;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:30px;
    color:#888;
}

/* MOBILE */
@media(max-width:600px){
    .item{
        flex-direction:column;
        align-items:flex-start;
        gap:12px;
    }

    .right{
        text-align:left;
    }

    .title{
        font-size:18px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="title">Halo Owner 👋</div>
    <div class="menu">
        <a href="index.php">Kembali</a>
        <a href="5logout.php">Logout</a>
    </div>
</div>

<div class="container">

<h2>Dashboard Omzet</h2>

<div class="filter">
    <a href="?filter=7hari" class="<?= $filter=='7hari'?'active':'' ?>">7 Hari</a>
    <a href="?filter=30hari" class="<?= $filter=='30hari'?'active':'' ?>">30 Hari</a>
    <a href="?filter=semua" class="<?= $filter=='semua'?'active':'' ?>">Semua</a>
    <a href="export_excell.php?filter=<?= $filter ?>" style="background:#8AFFBF;">Download Excel</a>
</div>

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

<?php if($top){ ?>
<div class="box">
    <h3>⭐ Penjualan Tertinggi</h3>
    <p><?= date('d M Y', strtotime($top['tanggal'])) ?> — <b>Rp <?= number_format($top['omzet']) ?></b></p>
</div>
<?php } ?>

<div class="box">
    <h3>Data Omzet</h3>

    <?php if(mysqli_num_rows($query) > 0){ ?>
        <div class="list-data">

        <?php $no=1; while($row=mysqli_fetch_assoc($query)){ ?>
            <div class="item">
                <div class="left">
                    <div class="no">#<?= $no++ ?></div>
                    <div>
                        <div class="tanggal"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                        <div class="kasir"><?= $row['nama_kasir'] ?></div>
                    </div>
                </div>

                <div class="right">
                    <div class="omzet">Rp <?= number_format($row['omzet']) ?></div>
                    <div class="trx"><?= $row['total_transaksi'] ?> transaksi</div>
                </div>
            </div>
        <?php } ?>

        </div>
    <?php } else { ?>
        <div class="empty">Tidak ada data 😢</div>
    <?php } ?>

</div>

</div>

</body>
</html>