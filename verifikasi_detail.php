<?php
include "1koneksi.php";

$id = $_GET['id'] ?? 0;

// Ambil data laporan
$data = mysqli_fetch_assoc(mysqli_query($connection,
    "SELECT * FROM laporan_harian WHERE id_laporan='$id'"
));

// Data sistem
$omzet = $data['total_omzet'] ?? 0;
$debit = $data['omzet_debit'] ?? 0;
$total_transaksi = $data['total_transaksi'] ?? 0;

// Handle submit
if(isset($_POST['simpan'])){
    $cash = $_POST['cash_real'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];

    $selisih = $omzet - ($cash + $debit);

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
    padding:25px;
    max-width:1000px;
    margin:auto;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:14px;
    margin-bottom:20px;
    box-shadow:0 6px 15px rgba(0,0,0,0.06);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-2px);
}

/* TITLE */
.title{
    font-size:20px;
    font-weight:600;
    margin-bottom:15px;
}

/* FLEX */
.flex{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

/* BOX (DATA SYSTEM) */
.box{
    flex:1;
    padding:15px;
    border-radius:12px;
    color:white;
    font-weight:500;
}

/* WARNA BIAR HIDUP */
.box:nth-child(1){
    background: linear-gradient(135deg, #6366f1, #4f46e5);
}
.box:nth-child(2){
    background: linear-gradient(135deg, #22c55e, #16a34a);
}
.box:nth-child(3){
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
    margin-top:8px;
    font-size:14px;
}

/* KEPUTUSAN CARD */
.keputusan{
    background:#f8fafc;
}

/* LABEL */
.label{
    margin:10px 0 5px;
    display:block;
    font-size:14px;
}

.radio-item:hover{
    border-color:#2F00FF;
}

/* TEXTAREA */
textarea{
    width:100%;
    border-radius:10px;
    border:1px solid #ddd;
    padding:10px;
    min-height:90px;
    margin-bottom:15px;
}

/* BUTTON */
.btn-submit{
    width:100%;
    background: linear-gradient(135deg, #2F00FF, #4f46e5);
    color:white;
    padding:12px;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-submit:hover{
    opacity:0.9;
}

/* RADIO GROUP */
.radio-group{
    display:flex;
    gap:15px;
}

/* BASE RADIO */
.radio-item{
    flex:1;
    text-align:center;
    padding:15px;
    border-radius:12px;
    border:2px solid #ddd;
    cursor:pointer;
    font-weight:600;
    position:relative;
    transition:0.2s;
}

/* HIDE RADIO ASLI */
.radio-item input{
    position:absolute;
    opacity:0;
}

/* DEFAULT TEXT */
.radio-item span{
    font-size:16px;
}

/* ACC STYLE */
.radio-item.acc{
    border-color:#1BB628;
    color:#1BB628;
}

/* ACC SELECTED */
.radio-item.acc input:checked + span{
    color:white;
}

.radio-item.acc:has(input:checked){
    background:#1BB628;
    color:white;
    border-color:#1BB628;
}

/* TOLAK STYLE */
.radio-item.tolak{
    border-color:#FF383C;
    color:#FF383C;
}

/* TOLAK SELECTED */
.radio-item.tolak input:checked + span{
    color:white;
}

.radio-item.tolak:has(input:checked){
    background:#FF383C;
    color:white;
    border-color:#FF383C;
}

/* HOVER */
.radio-item:hover{
    transform:scale(1.02);
}

.input-group{
    display:flex;
    align-items:center;
    border:1px solid #ddd;
    border-radius:12px;
    overflow:hidden;
    background:white;
}

.prefix{
    padding:12px;
    background:#f1f5f9;
    font-weight:600;
    color:#555;
}

.input-group input{
    border:none;
    outline:none;
    padding:12px;
    width:100%;
    font-size:14px;
    text-align:right;
}


/* MOBILE */
@media(max-width:600px){
    .flex{
        flex-direction:column;
    }

    .header b{
        font-size:16px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div><b>Verifikasi Laporan</b></div>
    <a href="verifikasi.php">Kembali</a>
</div>

<div class="container">

<form method="POST">

<!-- DATA SISTEM -->
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
            Total Transaksi<br>
            <b><?= $total_transaksi ?></b>
        </div>
    </div>
</div>

<!-- INPUT ADMIN -->
<div class="input-group">
    <span class="prefix">Rp</span>
    <input type="number" name="cash_real" placeholder="0" required>
</div>

<!-- KEPUTUSAN -->
<div class="card keputusan">
    <h3>Keputusan</h3>

    <label class="label">Status</label>

    <div class="radio-group">
        <label class="radio-item acc">
            <input type="radio" name="status" value="ACC">
            <span>ACC</span>
        </label>

        <label class="radio-item tolak">
            <input type="radio" name="status" value="DITOLAK">
            <span>Tolak</span>
        </label>
    </div>

    <label class="label">Catatan (opsional)</label>
    <textarea name="catatan" placeholder="Tulis catatan jika diperlukan..."></textarea>

    <button class="btn-submit">Kirim & Simpan</button>
</div>
</form>

</div>

</body>
</html>