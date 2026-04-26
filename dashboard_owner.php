<?php
session_start();
include "1koneksi.php";

$filter = $_GET['filter'] ?? '7hari';
$where = "WHERE status='acc'";

if($filter == '7hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}elseif($filter == '30hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

$query = mysqli_query($connection,"SELECT * FROM laporan_harian $where ORDER BY tanggal ASC");

$total_omzet = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(omzet) as total FROM laporan_harian $where
"))['total'] ?? 0;

$total_transaksi = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(total_transaksi) as total FROM laporan_harian $where
"))['total'] ?? 0;

$jumlah = mysqli_num_rows($query);
$rata = $jumlah > 0 ? $total_omzet / $jumlah : 0;

$top = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT tanggal, omzet FROM laporan_harian $where ORDER BY omzet DESC LIMIT 1
"));
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard Owner</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    *{ box-sizing:border-box; margin:0; padding:0; }

    :root{
        --bg: #f5f7fb;
        --surface: #ffffff;
        --surface2: #f1f5f9;
        --accent: #16a34a;
        --accent2: #4f46e5;
        --accent3: #f97316;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e2e8f0;
    }

    body{
        font-family:'DM Sans', sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
    }

    .header{
        padding: 20px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .header-left{
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo{
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .title{
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        color: var(--text);
    }

    .menu{
        display: flex;
        gap: 8px;
    }

    .menu a{
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 8px;
        background: var(--surface2);
        color: var(--muted);
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
        border: 1px solid var(--border);
    }

    .menu a:hover{
        color: var(--text);
        background: #e2e8f0;
    }

    .container{
        padding: 32px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-title{
        font-family: 'DM Serif Display', serif;
        font-size: 32px;
        margin-bottom: 6px;
    }

    .page-sub{
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 28px;
    }

    .filter{
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .filter a{
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none;
        background: var(--surface);
        color: var(--muted);
        font-size: 13px;
        font-weight: 500;
        border: 1px solid var(--border);
        transition: all 0.2s;
    }

    .filter a:hover{ color: var(--text); background: var(--surface2); }

    .filter a.active{
        background: var(--accent2);
        color: white;
        border-color: var(--accent2);
        font-weight: 600;
    }

    .filter a.dl{
        background: #f0fdf4;
        color: var(--accent);
        border-color: #bbf7d0;
        margin-left: auto;
    }

    .filter a.dl:hover{
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .cards{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .card{
        padding: 24px;
        border-radius: 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .card-icon{
        font-size: 22px;
        margin-bottom: 12px;
    }

    .card-label{
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .card-value{
        font-family: 'DM Serif Display', serif;
        font-size: 26px;
    }

    .card.blue .card-value{ color: var(--accent2); }
    .card.green .card-value{ color: var(--accent); }
    .card.orange .card-value{ color: var(--accent3); }

    .top-box{
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .top-badge{ font-size: 28px; }

    .top-label{
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
    }

    .top-val{
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        color: var(--accent);
    }

    .box{
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .box-header{
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .box-title{ font-size: 15px; font-weight: 600; }

    .badge{
        background: var(--surface2);
        color: var(--muted);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        border: 1px solid var(--border);
    }

    table{ width:100%; border-collapse:collapse; }

    thead tr{ background: var(--surface2); }

    th{
        padding: 12px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
    }

    td{
        padding: 14px 20px;
        font-size: 14px;
        border-bottom: 1px solid var(--border);
        color: var(--text);
    }

    tbody tr:last-child td{ border-bottom: none; }
    tbody tr:hover{ background: var(--surface2); }

    .kasir-chip{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--surface2);
        border: 1px solid var(--border);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        color: var(--muted);
    }

    .omzet-val{ font-weight: 600; color: var(--accent); }
    .trx-val{ color: var(--muted); font-size: 13px; }

    .no-data{
        text-align: center;
        padding: 48px;
        color: var(--muted);
    }

    .no-data .icon{ font-size: 36px; margin-bottom: 8px; }

    @media(max-width:600px){
        .container{ padding: 16px; }
        .header{ padding: 14px 16px; }
        .filter a.dl{ margin-left: 0; }
    }
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <div class="title">Owner Dashboard</div>
    </div>
    <div class="menu">
        <a href="5logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <div class="page-title">Dashboard Omzet</div>
    <div class="page-sub">Pantau performa penjualan harian kasir</div>

    <div class="filter">
        <a href="?filter=7hari" class="<?= $filter=='7hari'?'active':'' ?>">7 Hari</a>
        <a href="?filter=30hari" class="<?= $filter=='30hari'?'active':'' ?>">30 Hari</a>
        <a href="?filter=semua" class="<?= $filter=='semua'?'active':'' ?>">Semua</a>
        <a href="export_excell.php?filter=<?= $filter ?>" class="dl">Download Excel</a>
    </div>

    <div class="cards">
        <div class="card blue">
            <div class="card-icon">💰</div>
            <div class="card-label">Total Omzet</div>
            <div class="card-value">Rp <?= number_format($total_omzet,0,',','.') ?></div>
        </div>
        <div class="card green">
            <div class="card-icon">🧾</div>
            <div class="card-label">Total Transaksi</div>
            <div class="card-value"><?= number_format($total_transaksi,0,',','.') ?></div>
        </div>
        <div class="card orange">
            <div class="card-icon">📈</div>
            <div class="card-label">Rata-rata / Hari</div>
            <div class="card-value">Rp <?= number_format($rata,0,',','.') ?></div>
        </div>
    </div>

    <?php if($top){ ?>
    <div class="top-box">
        <div class="top-badge">⭐</div>
        <div>
            <div class="top-label">Penjualan Tertinggi</div>
            <div class="top-val">
                <?= date('d M Y', strtotime($top['tanggal'])) ?> — Rp <?= number_format($top['omzet'],0,',','.') ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="box">
        <div class="box-header">
            <div class="box-title">Data Omzet</div>
            <div class="badge"><?= $jumlah ?> hari</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Omzet</th>
                    <th>Transaksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            mysqli_data_seek($query, 0);
            if(mysqli_num_rows($query) > 0){
                while($row = mysqli_fetch_assoc($query)){
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                    <td><span class="kasir-chip">👤 <?= $row['nama_kasir'] ?></span></td>
                    <td><span class="omzet-val">Rp <?= number_format($row['omzet'],0,',','.') ?></span></td>
                    <td><span class="trx-val"><?= $row['total_transaksi'] ?> transaksi</span></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="5">
                        <div class="no-data">
                            Tidak ada data untuk periode ini
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>