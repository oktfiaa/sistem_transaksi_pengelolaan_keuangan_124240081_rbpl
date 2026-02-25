<?php

session_start();
include "1koneksi.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    // cek kosong
    if(empty($username) || empty($password)){
        echo "Username dan password wajib diisi!";
        exit;
    }

    // ambil data user aktif
    $query = mysqli_query($connection,
        "SELECT * FROM users 
        WHERE username='$username' 
        AND status=1"
    );

    $data = mysqli_fetch_assoc($query);

    if($data){

        //pakai perbandingan biasa dulu
        if($password == $data['password']){

            // ===== SESSION =====
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role_id'];

            // redirect sesuai role
            if($data['role_id'] == 1){
                header("Location: 4dashboard_kasir.php");
            }
            elseif($data['role_id'] == 2){
                header("Location: dashboard_admin.php");
            }
            elseif($data['role_id'] == 3){
                header("Location: dashboard_owner.php");
            }

            exit;

        } else {
            echo "Password salah!";
        }

    } else {
        echo "Username tidak ditemukan atau tidak aktif!";
    }
}

?>