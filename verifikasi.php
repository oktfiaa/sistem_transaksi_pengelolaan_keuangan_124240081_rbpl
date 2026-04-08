<?php
session_start();
include "1koneksi.php";

// AMBIL DATA LAPORAN
$data = mysqli_query($connection, "SELECT * FROM laporan_harian ORDER BY id_laporan DESC");

// HITUNG STATUS
$menunggu = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM laporan_harian WHERE status='menunggu'"))['jml'];
$acc = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM laporan_harian WHERE status='acc'"))['jml'];
$tolak = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as jml FROM laporan_harian WHERE status='ditolak'"))['jml'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Verifikasi Laporan</title>
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

.btn-back{
    background:#D9D9D9;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
    color:black;
}

/* CONTAINER */
.container{
    padding:20px;
}

/* CARDS */
.cards{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:150px;
    padding:20px;
    border-radius:10px;
    color:white;
}

.orange{ background:#FF8D28; }
.green{ background:#1BB628; }
.red{ background:#FF383C; }

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

th{
    text-align:left;
}

/* STATUS */
.status{
    padding:5px 10px;
    border-radius:6px;
    font-size:12px;
    color:white;
}

.menunggu{ background:#FF8D28; }
.acc{ background:#1BB628; }
.tolak{ background:#FF383C; }

/* BUTTON */
.btn{
    padding:5px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
}

.btn-acc{ background:#1BB628; color:white; }
.btn-tolak{ background:#FF383C; color:white; }

/* MOBILE */
@media(max-width:600px){
    .title{
        font-size:18px;
    }

    th, td{
        font-size:12px;
        padding:8px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="title">Verifikasi Laporan Harian</div>
    <a href="dashboard_admin_keuangan.php" class="btn-back">Kembali</a>
</div>

<div class="container">

    <!-- CARD STATUS -->
    <div class="cards">
        <div class="card orange">
            <h2><?php echo $menunggu; ?></h2>
            <p>Menunggu</p>
        </div>

        <div class="card green">
            <h2><?php echo $acc; ?></h2>
            <p>Disetujui</p>
        </div>

        <div class="card red">
            <h2><?php echo $tolak; ?></h2>
            <p>Ditolak</p>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-box">
        <h3>Laporan Masuk</h3>

        <table>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Omzet</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php $no=1; while($row = mysqli_fetch_assoc($data)){ ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['tanggal']; ?></td>
                <td><?php echo $row['nama_kasir']; ?></td>
                <td>Rp <?php echo number_format($row['omzet']); ?></td>
                <td><?php echo $row['total_transaksi']; ?></td>

                <td>
                    <span class="status <?php echo $row['status']; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>

                <td>
                    <?php if($row['status']=='menunggu'){ ?>
                        <a href="proses_verifikasi.php?id=<?php echo $row['id_laporan']; ?>&aksi=acc">
                            <button class="btn btn-acc">ACC</button>
                        </a>
                        <a href="proses_verifikasi.php?id=<?php echo $row['id_laporan']; ?>&aksi=tolak">
                            <button class="btn btn-tolak">Tolak</button>
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>

</div>

</body>
</html>