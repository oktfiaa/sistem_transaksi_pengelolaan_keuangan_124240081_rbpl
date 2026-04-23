<?php
include "1koneksi.php";

$id = $_GET['id'] ?? 0;

// ambil data laporan
$data = mysqli_fetch_assoc(mysqli_query($connection,
    "SELECT * FROM laporan_harian WHERE id_laporan='$id'"
));

$tanggal = $data['tanggal'];
$nama_kasir = $data['nama_kasir'];

// 🔥 ambil dari transaksi
$query = mysqli_query($connection,"
    SELECT 
        SUM(CASE WHEN metode='non_tunai' THEN total_harga ELSE 0 END) as debit,
        SUM(CASE WHEN metode='tunai' THEN total_harga ELSE 0 END) as tunai,
        SUM(total_harga) as omzet
    FROM transaksi
    WHERE DATE(tanggal)='$tanggal'
");

$trx = mysqli_fetch_assoc($query);

$debit = $trx['debit'] ?? 0;
$tunai_sistem = $trx['tunai'] ?? 0;
$omzet = $trx['omzet'] ?? 0;

$total_transaksi = $data['total_transaksi'] ?? 0;


// 🔥 SIMPAN
if(isset($_POST['simpan'])){
    $cash = $_POST['cash_real'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];

    $selisih = $cash - $tunai_sistem;

    mysqli_query($connection,"
        UPDATE laporan_harian SET
        cash_real='$cash',
        selisih='$selisih',
        status='$status',
        catatan='$catatan'
        WHERE id_laporan='$id'
    ");

    echo "<script>alert('Berhasil disimpan!'); window.location='verifikasi.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi</title>

<style>
body{
    font-family:'Poppins', sans-serif;
    margin:0;
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
}

.header{
    background:white;
    padding:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.header b{font-size:18px;}

.header a{
    background:#e0e7ff;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    color:#2F00FF;
}

.container{
    padding:20px;
    max-width:1000px;
    margin:auto;
}

.card{
    background:white;
    padding:20px;
    border-radius:14px;
    margin-bottom:20px;
    box-shadow:0 6px 15px rgba(0,0,0,0.06);
}

.title{
    font-size:18px;
    font-weight:600;
    margin-bottom:10px;
}

.flex{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.box{
    flex:1;
    min-width:140px;
    padding:15px;
    border-radius:10px;
    color:white;
}

.box:nth-child(1){background:#4f46e5;}
.box:nth-child(2){background:#16a34a;}
.box:nth-child(3){background:#f59e0b;}
.box:nth-child(4){background:#6366f1;}

.input-group{
    display:flex;
    border:1px solid #ddd;
    border-radius:10px;
    overflow:hidden;
}

.prefix{
    padding:10px;
    background:#f1f5f9;
}

input{
    border:none;
    padding:10px;
    width:100%;
    text-align:right;
}

.radio-group{
    display:flex;
    gap:10px;
}

.radio-item{
    flex:1;
    text-align:center;
    padding:10px;
    border-radius:10px;
    border:2px solid #ddd;
    cursor:pointer;
}

.radio-item input{display:none;}

.radio-item.acc{border-color:#1BB628;color:#1BB628;}
.radio-item.tolak{border-color:#FF383C;color:#FF383C;}

.radio-item input:checked + span{
    color:white;
}

.radio-item.acc:has(input:checked){background:#1BB628;}
.radio-item.tolak:has(input:checked){background:#FF383C;}

textarea{
    width:100%;
    border-radius:10px;
    border:1px solid #ddd;
    padding:10px;
    margin-top:10px;
}

.btn{
    width:100%;
    background:#2F00FF;
    color:white;
    padding:12px;
    border:none;
    border-radius:10px;
    margin-top:10px;
    cursor:pointer;
}

@media(max-width:600px){
    .flex{flex-direction:column;}
}
</style>
</head>

<body>

<div class="header">
    <b>Verifikasi Laporan</b>
    <a href="verifikasi.php">Kembali</a>
</div>

<div class="container">

<form method="POST">

<div class="card">
    <div class="title">Data Sistem</div>

    <div class="flex">
        <div class="box">
            Total Omzet<br>
            <b>Rp <?= number_format($omzet) ?></b>
        </div>

        <div class="box">
            Omzet Debit<br>
            <b>Rp <?= number_format($debit) ?></b>
        </div>

        <div class="box">
            Tunai Sistem<br>
            <b>Rp <?= number_format($tunai_sistem) ?></b>
        </div>

        <div class="box">
            Total Transaksi<br>
            <b><?= $total_transaksi ?></b>
        </div>
    </div>
</div>

<div class="card">
    <div class="title">Input Uang Tunai (Real)</div>

    <div class="input-group">
        <span class="prefix">Rp</span>
        <input type="number" id="cash_real" name="cash_real" required onkeyup="hitungSelisih()">
    </div>

    <p id="selisihText" style="margin-top:10px; font-weight:600;"></p>
</div>

<div class="card">
    <div class="title">Keputusan</div>

    <div class="radio-group">
        <label class="radio-item acc">
            <input type="radio" name="status" value="acc" required>
            <span>ACC</span>
        </label>

        <label class="radio-item tolak">
            <input type="radio" name="status" value="ditolak">
            <span>Tolak</span>
        </label>
    </div>

    <textarea name="catatan" placeholder="Catatan jika ada selisih..."></textarea>

    <button type="submit" name="simpan" class="btn">Simpan</button>
</div>

</form>

</div>

<script>
function hitungSelisih(){
    let cash = document.getElementById("cash_real").value;
    let tunai = <?= $tunai_sistem ?>;

    if(cash === ""){
        document.getElementById("selisihText").innerHTML = "";
        return;
    }

    let selisih = cash - tunai;

    if(selisih == 0){
        document.getElementById("selisihText").innerHTML =
            "Tidak ada selisih";
        document.getElementById("selisihText").style.color = "green";
    }
    else if(selisih > 0){
        document.getElementById("selisihText").innerHTML =
            "Lebih Rp " + selisih.toLocaleString();
        document.getElementById("selisihText").style.color = "orange";
    }
    else{
        document.getElementById("selisihText").innerHTML =
            "Kurang Rp " + Math.abs(selisih).toLocaleString();
        document.getElementById("selisihText").style.color = "red";
    }
}
</script>

</body>
</html>