<!DOCTYPE html>
<html>
<head>
    <title>Sistem Informasi Transaksi & Pengelolaan Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 80px;
        }
        .card {
            display: inline-block;
            width: 220px;
            padding: 30px;
            margin: 20px;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }
        .kasir { background: linear-gradient(to right, #3a00ff, #6a00ff); }
        .admin { background-color: #16a34a; }
        .owner { background-color: #ec4899; }
    </style>
</head>
<body>

<h2>Pilih Role untuk Melanjutkan</h2>

<div class="container">
    <a class="card kasir" href="2login.php?role=1">
        KASIR<br><br>Masuk 
    </a>

    <a class="card admin" href="2login.php?role=2">
        ADMIN KEUANGAN<br><br>Masuk 
    </a>

    <a class="card owner" href="2login.php?role=3">
        OWNER<br><br>Masuk 
    </a>
</div>

</body>
</html>