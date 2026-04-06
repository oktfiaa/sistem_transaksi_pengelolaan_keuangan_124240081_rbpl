<?php
include "1koneksi.php";

$id_transaksi = $_GET['id'] ?? 0;

$query = mysqli_query($connection, "SELECT * FROM transaksi WHERE id_transaksi='$id_transaksi'");
$data = mysqli_fetch_assoc($query);

$tanggal = date("d F Y H:i", strtotime($data['tanggal']));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Berhasil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
        }

        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background: #8AFFBF;
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .title {
            font-size: 28px;
            font-weight: 600;
        }

        .subtitle {
            font-size: 16px;
            margin-top: 10px;
        }

        .divider {
            border-top: 2px solid #1BB628;
            margin: 20px 0;
        }

        .label {
            font-size: 16px;
        }

        .total {
            font-size: 32px;
            font-weight: 600;
            margin-top: 5px;
        }

        .box {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #ccc;
        }

        .trx {
            font-weight: 600;
            font-size: 18px;
        }

        .date {
            font-size: 13px;
            margin-top: 5px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            color: white;
            font-weight: 600;
        }

        .btn-print {
            background: #2F00FF;
        }

        .btn-save {
            background: #1BB628;
        }

        .note {
            font-size: 12px;
            margin-top: 15px;
        }

        /* MOBILE FIX */
        @media(max-width: 480px){
            .title {
                font-size: 22px;
            }

            .total {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card">

        <div class="title">PEMBAYARAN BERHASIL</div>
        <div class="subtitle">Transaksi berhasil diproses</div>

        <div class="divider"></div>

        <div class="label">TOTAL PEMBAYARAN</div>
        <div class="total">
            Rp <?php echo number_format($data['total_harga'],0,',','.'); ?>
        </div>

        <div class="box">
            <div>No Transaksi</div>
            <div class="trx">TRX-<?php echo $data['id_transaksi']; ?></div>
            <div class="date"><?php echo $tanggal; ?></div>
        </div>

        <div class="btn-group">
            <button class="btn btn-print" onclick="cetakStruk()">Cetak Struk</button>
            <button class="btn btn-save" onclick="kembaliKasir()">Simpan Transaksi</button>
        </div>

        <div class="note">
            Klik "Simpan Transaksi" untuk kembali ke halaman transaksi baru
        </div>

    </div>
</div>

<script>
function cetakStruk(){
    alert("Struk berhasil dicetak!");
}

function kembaliKasir(){
    window.location.href = "transaksi.php";
}
</script>

</body>
</html>