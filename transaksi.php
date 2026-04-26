<?php
session_start();
include "1koneksi.php";

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// HAPUS ITEM
if(isset($_GET['hapus'])){
    $index = $_GET['hapus'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// TAMBAH BARANG
if(isset($_POST['tambah'])){
    $kode = $_POST['kode_barang'];
    $qty  = (int)$_POST['qty'];

    $query = mysqli_query($connection, 
        "SELECT * FROM barang WHERE kode_barang='$kode'"
    );
    $barang = mysqli_fetch_assoc($query);

    if($barang){
        $found = false;

        foreach($_SESSION['cart'] as &$item){
            if($item['kode'] == $barang['kode_barang']){
                $item['qty'] += $qty;
                $item['subtotal'] = $item['qty'] * $item['harga'];
                $found = true;
                break;
            }
        }
        unset($item);

        if(!$found){
            $_SESSION['cart'][] = [
                'kode'     => $barang['kode_barang'],
                'nama'     => $barang['nama_barang'],
                'harga'    => $barang['harga'],
                'qty'      => $qty,
                'subtotal' => $barang['harga'] * $qty
            ];
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// HITUNG TOTAL
$total = 0;
$totalQty = 0;
foreach($_SESSION['cart'] as $item){
    $total += $item['subtotal'];
    $totalQty += $item['qty'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            margin:0; 
            font-family:'Poppins', sans-serif; 
            background:#f5f5f5;
        }
        .header {
            background:#F4F4F4;
            padding:20px 40px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0px 4px 4px rgba(0,0,0,0.2);
        }
        .title {
            font-size:28px;
            font-weight:600;
        }
        .header-right {
            display:flex;
            gap:15px;
        }
        .btn-header {
            background:#D9D9D9;
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
            color:black;
            font-size:14px;
            white-space:nowrap;
        }
        .btn-header:hover {
            background:#cfcfcf;
        }
        .container {
            display:flex;
            gap:20px;
            padding:20px;
        }
        .left, .right {
            background:white;
            padding:20px;
            border-radius:10px;
            flex:1;
            box-shadow:0px 4px 10px rgba(0,0,0,0.1);
        }
        h3 { margin-top:0; }
        input {
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
            box-sizing:border-box;
        }
        button {
            padding:10px;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }
        button:hover { opacity:0.9; }
        .btn-tunai {
            background:#16a34a;
            color:white;
            width:100%;
            margin-top:20px;
        }
        table { width:100%; border-collapse:collapse; }
        th, td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }
        .summary { background:#eef2ff; }
        .total { font-size:20px; font-weight:600; }
        .dropdown-hasil {
            border:1px solid #ccc;
            border-radius:8px;
            background:white;
            display:none;
            max-height:200px;
            overflow-y:auto;
            margin-top:-10px;
            margin-bottom:10px;
            box-shadow:0 4px 8px rgba(0,0,0,0.1);
            position:relative;
            z-index:100;
        }
        .dropdown-item {
            padding:10px;
            cursor:pointer;
            border-bottom:1px solid #eee;
            font-size:14px;
        }
        .dropdown-item:hover { background:#f0f0f0; }
        .modal {
            display:none;
            position:fixed;
            z-index:999;
            inset:0;
            background:rgba(0,0,0,0.5);
            justify-content:center;
            align-items:center;
        }
        .modal-content {
            background:white;
            padding:25px;
            width:90%;
            max-width:320px;
            border-radius:12px;
            text-align:center;
        }
        @media(max-width:768px){
            .container{ flex-direction:column; }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">Kasir</div>
    <div class="header-right">
        <a href="rekap.php" class="btn-header">Lihat Rekap Harian</a>
        <a href="4dashboard_kasir.php" class="btn-header">Kembali</a>
    </div>
</div>

<div class="container">
    <div class="left">
        <h3>Input Barang</h3>
        <form method="POST">
            <label>Kode / Nama Barang</label>
            <input 
                type="text" 
                id="inputCari" 
                placeholder="Ketik kode atau nama barang..." 
                autocomplete="off"
            >
            <input type="hidden" name="kode_barang" id="kodeBarang" required>

            <div id="hasilCari" class="dropdown-hasil"></div>

            <label>Jumlah</label>
            <input type="number" name="qty" min="1" required>
            <button type="submit" name="tambah">+ Tambah ke Keranjang</button>
        </form>

        <hr>
        <h3>Keranjang</h3>
        <table>
            <tr>
                <th>Nama</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($_SESSION['cart'] as $i => $item): ?>
            <tr>
                <td><?= $item['nama'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                <td>
                    <a href="?hapus=<?= $i ?>" 
                       onclick="return confirm('Hapus item ini?')" 
                       style="color:red;text-decoration:none;font-weight:bold;">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="right summary">
        <h3>Ringkasan Pembayaran</h3>
        <p>Jumlah Item: <?= count($_SESSION['cart']) ?></p>
        <p>Total Qty: <?= $totalQty ?></p>
        <hr>
        <p class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>

        <form method="POST" action="proses_transaksi.php">
            <input type="hidden" name="metode" value="tunai">
            <button type="submit" class="btn-tunai" <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>>
                Bayar Tunai
            </button>
        </form>

        <button 
            onclick="openModal()" 
            style="background:#1BB628;color:white;padding:12px;border-radius:10px;cursor:pointer;margin-top:10px;width:100%;"
            <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>>
            Bayar Non-Tunai
        </button>
    </div>
</div>

<!-- Modal PIN -->
<div id="modalPembayaran" class="modal">
    <div class="modal-content">
        <h3>Masukkan PIN</h3>
        <form method="POST" action="proses_transaksi.php">
            <input type="hidden" name="metode" value="non_tunai">
            <input type="password" name="pin" placeholder="PIN" required>
            <button type="submit" style="background:#1BB628;color:white;padding:12px;border-radius:10px;margin-top:10px;">
                Bayar
            </button>
        </form>
        <button onclick="closeModal()" style="margin-top:10px;">Batal</button>
    </div>
</div>

<script>
const inputCari  = document.getElementById('inputCari');
const hasilCari  = document.getElementById('hasilCari');
const kodeBarang = document.getElementById('kodeBarang');

inputCari.addEventListener('input', function(){
    const keyword = this.value.trim();

    if(keyword.length < 1){
        hasilCari.style.display = 'none';
        hasilCari.innerHTML = '';
        return;
    }

    fetch('cari_barang.php?q=' + encodeURIComponent(keyword))
        .then(res => res.json())
        .then(data => {
            hasilCari.innerHTML = '';

            if(data.length === 0){
                hasilCari.innerHTML = '<div class="dropdown-item" style="color:#999;">Barang tidak ditemukan</div>';
                hasilCari.style.display = 'block';
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'dropdown-item';
                div.textContent = item.kode_barang + ' — ' + item.nama_barang + ' (Rp ' + parseInt(item.harga).toLocaleString('id-ID') + ')';

                div.addEventListener('click', () => {
                    inputCari.value  = item.nama_barang;
                    kodeBarang.value = item.kode_barang;
                    hasilCari.style.display = 'none';
                });

                hasilCari.appendChild(div);
            });

            hasilCari.style.display = 'block';
        });
});

// tutup dropdown kalau klik di luar
document.addEventListener('click', function(e){
    if(!hasilCari.contains(e.target) && e.target !== inputCari){
        hasilCari.style.display = 'none';
    }
});

function openModal() {
    document.getElementById("modalPembayaran").style.display = "flex";
}
function closeModal() {
    document.getElementById("modalPembayaran").style.display = "none";
}

document.querySelector('form').addEventListener('submit', function(e){
    console.log('kode_barang:', kodeBarang.value);
    console.log('inputCari:', inputCari.value);
    
    if(!kodeBarang.value){
        e.preventDefault();
        alert('Kode barang kosong! Pilih dari dropdown dulu.');
        inputCari.focus();
    }
});
</script>

</body>
</html>