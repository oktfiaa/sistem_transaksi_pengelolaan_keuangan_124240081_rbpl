<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
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
    text-align:center;
    box-shadow:0 4px 4px rgba(0,0,0,0.2);
}

.title{
    font-size:26px;
    font-weight:600;
}

/* WELCOME */
.welcome{
    text-align:center;
    margin-top:30px;
}

.welcome h1{
    margin:0;
    font-size:32px;
}

.welcome p{
    color:#666;
    margin-top:5px;
}

/* MENU */
.menu{
    max-width:400px;
    margin:30px auto;
    display:flex;
    flex-direction:column;
    gap:15px;
    padding:0 20px;
}

.menu a{
    text-decoration:none;
    background:white;
    padding:15px;
    border-radius:12px;
    text-align:center;
    color:black;
    font-weight:500;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    transition:0.2s;
}

.menu a:hover{
    transform:scale(1.02);
}

/* BUTTON COLORS */
.transaksi{
    background:#1BB628;
    color:white;
}

.rekap{
    background:#2F00FF;
    color:white;
}

.logout{
    background:#FF4D4D;
    color:white;
}

/* MOBILE */
@media(max-width:600px){
    .welcome h1{
        font-size:24px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="title">Dashboard Kasir</div>
</div>

<div class="welcome">
    <h1>Halo, <?php echo $_SESSION['nama']; ?> </h1>
    <p>Selamat bekerja hari ini</p>
</div>

<div class="menu">
    <a href="transaksi.php" class="transaksi"> Mulai Transaksi</a>
    <a href="#" > Notifikasi Verifikasi</a>
    <a href="rekap.php" class="rekap"> Lihat Rekap Harian</a>
    <a href="5logout.php" class="logout"> Logout</a>
</div>

</body>
</html>