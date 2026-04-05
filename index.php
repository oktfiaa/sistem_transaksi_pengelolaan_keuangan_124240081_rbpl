<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Keuangan</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: white;
        }

        .header {
            background: #F4F4F4;
            padding: 30px 80px;
            font-size: 32px;
            font-weight: 600;
            box-shadow: 0px 4px 4px rgba(0,0,0,0.25);
        }

        .subtitle {
            text-align: center;
            margin-top: 40px;
            font-size: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .card {
            width: 300px;
            height: 260px;
            border-radius: 12px;
            padding: 20px;
            text-decoration: none;
            box-shadow: 0px 4px 4px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .kasir {
            background: linear-gradient(to right, #3a00ff, #6a00ff);
            color: white;
        }

        .admin {
            background: #1BB628;
            color: #8AFFBF;
        }

        .owner {
            background: #FF8CE2;
            color: #8A226F;
        }

        .icon {
            width: 70px;
            margin-bottom: 15px;
        }

        .title-card {
            font-size: 22px;
            font-weight: 700;
        }

        .desc {
            font-size: 16px;
            margin-top: 5px;
        }

        .masuk {
            margin-top: 10px;
            font-size: 16px;
            text-decoration: underline;
        }

        .card:hover {
            transform: scale(1.05);
            transition: 0.2s;
        }

        @media (max-width: 768px) {
        .header {
            padding: 20px;
            font-size: 22px;
            text-align: center;
        }

        .subtitle {
            font-size: 16px;
            margin-top: 20px;
        }

        .container {
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            width: 90%;
            height: auto;
            padding: 20px;
        }

        .icon {
            width: 50px;
        }

        .title-card {
            font-size: 18px;
        }

        .desc {
            font-size: 14px;
        }

        .masuk {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>

<div class="header">
    Sistem Manajemen Keuangan
</div>

<div class="subtitle">
    Pilih Role untuk Melanjutkan
</div>

<div class="container">

    <!-- KASIR -->
    <a href="2login.php?role=1" class="card kasir">
        <img src="assets/kasir.png" class="icon">
        <div class="title-card">KASIR</div>
        <div class="desc">Transaksi & Laporan Harian</div>
        <div class="masuk">Masuk →</div>
    </a>

    <!-- ADMIN -->
    <a href="2login.php?role=2" class="card admin">
        <img src="assets/adminkeuangan.png" class="icon">
        <div class="title-card">Admin Keuangan</div>
        <div class="desc">Verifikasi & Laporan</div>
        <div class="masuk">Masuk →</div>
    </a>

    <!-- OWNER -->
    <a href="2login.php?role=3" class="card owner">
        <img src="assets/owner.png" class="icon">
        <div class="title-card">Owner</div>
        <div class="desc">Dashboard & Analytics</div>
        <div class="masuk">Masuk →</div>
    </a>

</div>

</body>
</html>