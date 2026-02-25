<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: 3proses_login.php");
    exit;
}
?>

<h2>Dashboard Kasir</h2>
<p>Halo <?php echo $_SESSION['nama']; ?></p>

<a href="">Logout</a>