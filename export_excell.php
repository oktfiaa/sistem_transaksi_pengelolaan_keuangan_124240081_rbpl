<?php
include "1koneksi.php";

$filter = $_GET['filter'] ?? '7hari';
$where = "WHERE status='acc'";

if($filter == '7hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}elseif($filter == '30hari'){
    $where .= " AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

$data = mysqli_query($connection,"SELECT * FROM laporan_harian $where ORDER BY tanggal ASC");

// SUMMARY
$summary = mysqli_fetch_assoc(mysqli_query($connection,"
    SELECT SUM(omzet) as total_omzet, SUM(total_transaksi) as total_trx 
    FROM laporan_harian $where
"));

$label_filter = $filter == '7hari' ? '7 Hari Terakhir' : ($filter == '30hari' ? '30 Hari Terakhir' : 'Semua Data');

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Omzet_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
<head>
<style>
    body{ 
        font-family: Arial, sans-serif; 
    }
    .judul{ 
        font-size:16pt; 
        font-weight:bold; 
    }
    .sub{ 
        font-size:10pt; 
        color:#555; 
    }
    .header-col{ 
        background:#1a1d27; 
        color:white; 
        font-weight:bold; 
        text-align:center; 
        padding:8px; }
    .data-row td{ 
        padding:6px 10px; 
        border:1px solid #ddd; }
    .data-row:nth-child(even) 
    td{ 
        background:#f9fafb;
    }
    .summary-row td{ 
        background:#e6f4ea; 
        font-weight:bold; 
        padding:6px 10px; 
        border:1px solid #ccc; }
    .number{ 
        text-align:right; 
        mso-number-format:'\#\,\#\#0'; 
    }
    .center{ 
        text-align:center; 
        }
</style>
</head>
<body>

<table>
    <tr><td class="judul" colspan="5">Laporan Omzet Penjualan</td></tr>
    <tr><td class="sub" colspan="5">Periode: <?= $label_filter ?> &nbsp;|&nbsp; Dicetak: <?= date('d M Y H:i') ?></td></tr>
    <tr><td colspan="5"></td></tr>

    <tr>
        <td class="header-col">No</td>
        <td class="header-col">Tanggal</td>
        <td class="header-col">Nama Kasir</td>
        <td class="header-col">Omzet (Rp)</td>
        <td class="header-col">Total Transaksi</td>
    </tr>

    <?php
    $no = 1;
    while($row = mysqli_fetch_assoc($data)){
        $tgl = date('d M Y', strtotime($row['tanggal']));
        echo "<tr class='data-row'>
            <td class='center'>$no</td>
            <td>$tgl</td>
            <td>{$row['nama_kasir']}</td>
            <td class='number'>" . number_format($row['omzet'],0,',','.') . "</td>
            <td class='center'>{$row['total_transaksi']}</td>
        </tr>";
        $no++;
    }
    ?>

    <tr><td colspan="5"></td></tr>
    <tr class="summary-row">
        <td colspan="3" style="text-align:right;">TOTAL</td>
        <td class="number"><?= number_format($summary['total_omzet'],0,',','.') ?></td>
        <td class="center"><?= $summary['total_trx'] ?></td>
    </tr>
</table>

</body>
</html>