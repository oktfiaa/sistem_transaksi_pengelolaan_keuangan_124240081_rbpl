<?php
include "1koneksi.php";

$keyword = $_GET['q'] ?? '';

$query = mysqli_query($connection,
    "SELECT * FROM barang 
     WHERE kode_barang LIKE '%$keyword%' 
     OR nama_barang LIKE '%$keyword%'
     LIMIT 10"
);

$hasil = [];
while($row = mysqli_fetch_assoc($query)){
    $hasil[] = $row;
}

echo json_encode($hasil);