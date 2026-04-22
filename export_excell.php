<?php
include "1koneksi.php";

$filter = $_GET['filter'] ?? '7hari';

$where = "";
if($filter == '7hari'){
    $where = "WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}elseif($filter == '30hari'){
    $where = "WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_omzet.xls");

$data = mysqli_query($connection,"
    SELECT * FROM laporan_harian 
    $where AND status='acc'
");

echo "<table border='1'>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Kasir</th>
<th>Omzet</th>
<th>Total Transaksi</th>
</tr>";

$no=1;
while($row=mysqli_fetch_assoc($data)){
    echo "<tr>
    <td>$no</td>
    <td>{$row['tanggal']}</td>
    <td>{$row['nama_kasir']}</td>
    <td>{$row['omzet']}</td>
    <td>{$row['total_transaksi']}</td>
    </tr>";
    $no++;
}

echo "</table>";
?>