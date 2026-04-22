<?php
session_start();
include "1koneksi.php";

// ambil semua data
$data = mysqli_query($connection, "SELECT * FROM laporan_harian ORDER BY id_laporan DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifikasi Kasir</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:#f4f6fb;
}

/* HEADER */
.header{
    background:#F4F4F4;
    padding:20px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.title{
    font-size:28px;
    font-weight:600;
}

.sub{
    font-size:14px;
    color:#555;
}

.menu{
    display:flex;
    gap:10px;
}

.menu a{
    background:#D9D9D9;
    padding:10px 15px;
    border-radius:10px;
    text-decoration:none;
    color:black;
    font-size:14px;
}

/* CONTAINER */
.container{
    padding:20px;
}

/* TABLE BOX */
.box{
    background:#e6ecff;
    border-radius:15px;
    padding:20px;
    overflow-x:auto;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    min-width:800px;
}

th{
    text-align:left;
    padding:12px;
    font-weight:600;
}

td{
    padding:12px;
    font-size:14px;
}

/* STATUS ICON */
.status{
    font-size:20px;
}

.menunggu{ color:#FF8D28; }
.acc{ color:#1BB628; }
.tolak{ color:#FF383C; }

/* BUTTON */
.btn{
    padding:6px 12px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
}

.btn-aksi{
    background:#FF8D28;
    color:white;
}

/* MOBILE */
@media(max-width:600px){
    .title{
        font-size:20px;
    }

    table{
        font-size:12px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div>
        <div class="title">Kasir</div>
        <div class="sub">Sistem Manajemen Keuangan</div>
    </div>

    <div class="menu">
        <a href="transaksi.php">Input Transaksi</a>
        <a href="rekap.php">Lihat Rekap Harian</a>
        <a href="4dashboard_kasir.php">Kembali</a>
    </div>
</div>

<div class="container">
    <div class="box">

        <table>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Kasir</th>
                <th>Omzet Harian</th>
                <th>Total Transaksi</th>
                <th>Status</th>
                <th>Alasan</th>
                <th>Aksi</th>
            </tr>

            <?php $no=1; while($row = mysqli_fetch_assoc($data)){ ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['nama_kasir'] ?></td>
                <td>Rp <?= number_format($row['omzet']) ?></td>
                <td><?= $row['total_transaksi'] ?></td>

                <!-- STATUS -->
                <td>
                    <?php if($row['status']=='menunggu'){ ?>
                        <span class="status menunggu">⏳</span>
                    <?php } elseif($row['status']=='acc'){ ?>
                        <span class="status acc">✔</span>
                    <?php } else { ?>
                        <span class="status tolak">✖</span>
                    <?php } ?>
                </td>

                <!-- CATATAN -->
                <td><?= $row['catatan'] ?? '-' ?></td>

                <!-- AKSI KASIR -->
                <td>
                    <?php if($row['status']=='ditolak'){ ?>
                        <?php if(empty($row['aksi_kasir'])){ ?>
                            <a href="aksi_kasir.php?id=<?= $row['id_laporan'] ?>">
                                <button class="btn btn-aksi">Tindak</button>
                            </a>
                        <?php } else { ?>
                            <small>✔ Sudah ditindak</small>
                        <?php } ?>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>

        </table>

    </div>
</div>

</body>
</html>