<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] != '1'){
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Transaksi</title>
</head>
<body>

<h2>Input Transaksi</h2>
<p>Kasir: <?php echo $_SESSION['nama']; ?></p>

<form method="POST" action="proses_transaksi.php">

    Nominal:
    <input type="number" name="nominal[]" required>
    <button type="button" onclick="tambahInput()">Tambah</button>

    <div id="tambahan"></div>

    <br><br>

    Metode Bayar:
    <select name="metode_bayar">
        <option value="Tunai">Tunai</option>
        <option value="Non Tunai">Non Tunai</option>
    </select>

    <br><br>

    <button type="submit" name="simpan">Simpan Transaksi</button>

</form>

<!-- <script>
function tambahInput(){
    var div = document.getElementById("tambahan");
    div.innerHTML += '<br>Nominal: <input type="number" name="nominal[]" required> <button type="button" onclick="this.parentNode.remove()">Hapus</button>';
}
</script> -->

</body>
</html>