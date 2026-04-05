<?php
$role = $_GET['role'] ?? '';

// tentukan warna + nama + gambar
if($role == 1){
    $roleName = "Kasir";
    $color = "linear-gradient(to right, #3a00ff, #6a00ff)";
    $image = "assets/kasir.png";
}
elseif($role == 2){
    $roleName = "Admin Keuangan";
    $color = "#16a34a";
    $image = "assets/adminkeuangan.png";
}
elseif($role == 3){
    $roleName = "Owner";
    $color = "#FF8CE2";
    $image = "assets/owner.png";
} else {
    $roleName = "User";
    $color = "#333";
    $image = "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: white;
        }

        .header {
            background: #F4F4F4;
            padding: 30px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 4px rgba(0,0,0,0.2);
        }

        .title {
            font-size: 28px;
            font-weight: 600;
        }

        .btn-kembali {
            background: #D9D9D9;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: black;
        }

        .container {
            text-align: center;
            margin-top: 80px;
        }

        .role-card {
            width: 320px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            color: white;
            font-size: 24px;
            font-weight: 600;
            background: <?php echo $color; ?>;
            margin-bottom: 30px;

            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .role-card img {
            width: 60px;
            margin-bottom: 10px;
        }

        .form-box {
            width: 400px;
            margin: auto;
            text-align: left;
        }

        label {
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.3);
        }

        .btn-login {
            width: 100%;
            background: <?php echo $color; ?>;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
        }

        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

<div class="header">
    <div class="title">Sistem Manajemen Keuangan</div>
    <a href="index.php" class="btn-kembali">Kembali</a>
</div>

<div class="container">

    <!-- CARD ROLE + GAMBAR -->
    <div class="role-card">
        <?php if($image != ""): ?>
            <img src="<?php echo $image; ?>">
        <?php endif; ?>

        Login <?php echo $roleName; ?>
    </div>

    <div class="form-box">
        <form method="POST" action="3proses_login.php">

            <input type="hidden" name="role" value="<?php echo $role; ?>">

            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username">

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password">

            <button type="submit" name="login" class="btn-login">LOGIN</button>

        </form>
    </div>

</div>

</body>
</html>
