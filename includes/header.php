<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();
}

$role = $_SESSION['role'] ?? null;
$nama = $_SESSION['nama'] ?? null;

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
<?= htmlspecialchars($pageTitle ?? 'Apotek') ?>
</title>

<link rel="stylesheet" href="/apotek-web/assets/css/style.css">

</head>

<body>

<header class="topbar">

    <div class="brand">
        💊 Apotek Sehat
    </div>

    <nav>

    <?php

    if ($role == 'admin') {

        echo '
        <a href="/apotek-web/admin/dashboard.php">Dashboard</a>
        <a href="/apotek-web/admin/users.php">Users</a>
        <a href="/apotek-web/admin/obat.php">Obat</a>
        <a href="/apotek-web/admin/pesanan.php">Pesanan</a>
        <a href="/apotek-web/admin/pembayaran.php">Pembayaran</a>
        <a href="/apotek-web/admin/pengiriman.php">Pengiriman</a>
        ';

    }

    elseif ($role == 'apoteker') {

        echo '
        <a href="/apotek-web/apoteker/dashboard.php">Dashboard</a>
        <a href="/apotek-web/apoteker/konsultasi.php">Konsultasi</a>
        <a href="/apotek-web/apoteker/obat.php">Obat</a>
        ';

    }

    elseif ($role == 'pelanggan') {

        echo '
        <a href="/apotek-web/pelanggan/dashboard.php">Dashboard</a>
        <a href="/apotek-web/pelanggan/obat.php">Beli Obat</a>
        <a href="/apotek-web/pelanggan/pesanan.php">Pesanan Saya</a>
        <a href="/apotek-web/pelanggan/profil.php">Profil</a>
        ';

    }

    ?>

    <span class="user">
        👤 <?= htmlspecialchars($nama) ?>
        (<?= htmlspecialchars($role) ?>)
    </span>

    <a class="logout" href="/apotek-web/logout.php">
        Logout
    </a>

    </nav>

</header>

<main class="container">