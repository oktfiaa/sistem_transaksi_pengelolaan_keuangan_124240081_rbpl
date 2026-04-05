<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] != '1'){
    header("Location: index.php");
    exit;
}
?>

<!-- <!DOCTYPE html>
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

</form> -->

<!-- <script>
function tambahInput(){
    var div = document.getElementById("tambahan");
    div.innerHTML += '<br>Nominal: <input type="number" name="nominal[]" required> <button type="button" onclick="this.parentNode.remove()">Hapus</button>';
}
</script> -->
<!-- 
</body>
</html> -->

<?php
session_start();
include "1koneksi.php";

//tambah barang
if(isset($_POST['tambah'])){
    $kode = $_POST['kode_barang'];
    $qty  = $_POST['qty'];

    $query = mysqli_query($connection, 
        "SELECT * FROM barang WHERE kode_barang='$kode'"
    );

    $barang = mysqli_fetch_assoc($query);

    if($barang){
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        $item = [
            'nama' => $barang['nama_barang'],
            'harga' => $barang['harga'],
            'qty' => $qty,
            'subtotal' => $barang['harga'] * $qty
        ];

        $_SESSION['cart'][] = $item;
    } else {
        echo "Barang tidak ditemukan!";
    }
}
?>

<h2>Transaksi</h2>

<form method="POST">
    Kode Barang:
    <input type="text" name="kode_barang">

    Jumlah:
    <input type="number" name="qty">

    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h3>Keranjang</h3>

<table border="1">
<tr>
    <th>Nama</th>
    <th>Qty</th>
    <th>Harga</th>
    <th>Subtotal</th>
</tr>

<?php
$total = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        echo "<tr>
            <td>{$item['nama']}</td>
            <td>{$item['qty']}</td>
            <td>{$item['harga']}</td>
            <td>{$item['subtotal']}</td>
        </tr>";

        $total += $item['subtotal'];
    }
}
?>

</table>

<h3>Total: Rp <?php echo $total; ?></h3>