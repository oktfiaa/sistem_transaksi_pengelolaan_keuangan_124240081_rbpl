<?php
$role = $_GET['role'] ?? '';
?>

!DOCTYPE html>
<html>
<head>
    <title>Login Sistem</title>
</head>
<body>

<h2>Login Sistem Informasi Transaksi & Pengelolaan Laporan Keuangan</h2>

<form method="POST" action="3proses_login.php">

    <input type="hidden" name="role" value="<?php echo $role; ?>">

    Username:<br>
    <input type="text" name="username"><br><br>

    Password:<br>
    <input type="password" name="password"><br><br>

    <button type="submit" name="login">Login</button>

</form>

</body>
</html>
