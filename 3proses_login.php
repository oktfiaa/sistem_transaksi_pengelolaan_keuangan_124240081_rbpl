<?php
session_start();
include "1koneksi.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // 1. CEK KOSONG
    if(empty($username) || empty($password)){
        echo "<script>
                alert('Username dan password wajib diisi!');
                window.location='2login.php?role=$role';
              </script>";
        exit;
    }

    // 2. CEK USERNAME ADA / TIDAK
    $cekUser = mysqli_query($connection,
        "SELECT * FROM users WHERE username='$username'"
    );

    $dataUser = mysqli_fetch_assoc($cekUser);

    if(!$dataUser){
        echo "<script>
                alert('Username tidak ditemukan!');
                window.location='2login.php?role=$role';
              </script>";
        exit;
    }

    // 3. CEK STATUS AKTIF
    if($dataUser['status'] != '1'){
        echo "<script>
                alert('Akun tidak aktif!');
                window.location='2login.php?role=$role';
              </script>";
        exit;
    }

    // 4. CEK ROLE
    if($dataUser['role_id'] != $role){
        echo "<script>
                alert('Role tidak sesuai!');
                window.location='2login.php?role=$role';
              </script>";
        exit;
    }

    // 5. CEK PASSWORD
    if($password != $dataUser['password']){
        echo "<script>
                alert('Password salah!');
                window.location='2login.php?role=$role';
              </script>";
        exit;
    }

    // 6. LOGIN BERHASIL
    $_SESSION['user_id'] = $dataUser['user_id'];
    $_SESSION['username'] = $dataUser['username'];
    $_SESSION['nama'] = $dataUser['nama'];
    $_SESSION['role'] = $dataUser['role_id'];

    // REDIRECT
    if($role == 1){
        header("Location: 4dashboard_kasir.php");
    } elseif($role == 2){
        header("Location: dashboard_admin_keuangan.php");
    } elseif($role == 3){
        header("Location: dashboard_owner.php");
    }

    exit;
}
?>