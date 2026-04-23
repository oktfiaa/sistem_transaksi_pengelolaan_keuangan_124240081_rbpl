<?php
include "1koneksi.php";

$id = $_GET['id'];

if(isset($_POST['simpan'])){
    $aksi = $_POST['aksi'];

    mysqli_query($connection,"
        UPDATE laporan_harian 
        SET aksi_kasir='$aksi', status='revisi'
        WHERE id_laporan='$id'
    ");

    echo "<script>alert('Berhasil dikirim'); location='notifikasi_kasir.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tindak Lanjut Kasir</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
}

/* HEADER */
.header{
    background:white;
    padding:18px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.header b{
    font-size:20px;
}

.header a{
    background:#e0e7ff;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    color:#2F00FF;
    font-weight:500;
}

/* CONTAINER */
.container{
    max-width:700px;
    margin:40px auto;
    padding:20px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* TITLE */
.title{
    font-size:22px;
    font-weight:600;
    margin-bottom:10px;
}

/* DESC */
.desc{
    font-size:14px;
    color:#555;
    margin-bottom:20px;
}

/* TEXTAREA */
textarea{
    width:100%;
    border-radius:12px;
    border:1px solid #ddd;
    padding:12px;
    min-height:120px;
    font-size:14px;
    resize:none;
    outline:none;
    transition:0.2s;
}

textarea:focus{
    border-color:#2F00FF;
    box-shadow:0 0 0 2px rgba(47,0,255,0.1);
}

/* BUTTON */
.btn{
    width:100%;
    margin-top:15px;
    background: linear-gradient(135deg, #2F00FF, #4f46e5);
    color:white;
    padding:12px;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn:hover{
    opacity:0.9;
}

/* ALERT NOTE */
.note{
    background:#fff7ed;
    border-left:4px solid #f59e0b;
    padding:10px;
    border-radius:8px;
    font-size:13px;
    margin-bottom:15px;
    color:#92400e;
}

/* MOBILE */
@media(max-width:600px){
    .container{
        margin:20px;
        padding:10px;
    }

    .title{
        font-size:18px;
    }
}
</style>
</head>

<body>

<div class="header">
    <b>Tindak Lanjut Kasir</b>
    <a href="notifikasi_kasir.php">Kembali</a>
</div>

<div class="container">

<form method="POST">

<div class="card">

    <div class="title">Tindaklanjuti Laporan</div>
    <div class="desc">Silakan isi tindakan yang sudah dilakukan terkait selisih atau catatan dari admin.</div>

    <div class="note">
        ⚠️ Contoh: "Sudah mengganti selisih Rp 20.000 ke kas"
    </div>

    <textarea name="aksi" placeholder="Tulis tindakan yang sudah dilakukan..." required></textarea>

    <button type="submit" name="simpan" class="btn">Kirim Tindak Lanjut</button>

</div>

</form>

</div>

</body>
</html>