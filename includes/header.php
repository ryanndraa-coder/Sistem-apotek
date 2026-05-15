<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= isset($pageTitle)
            ? $pageTitle . ' - Apotek Sehat'
            : 'Apotek Sehat'
        ?>
    </title>

    <link
        rel="stylesheet"
        href="/assets/css/style.css"
    >

</head>

<body>

<header class="topbar">

    <div class="brand">
        💊 Apotek Sehat
    </div>

    <nav>

        <!-- ================= ADMIN ================= -->

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>

            <a href="/admin/dashboard.php">
                Dashboard
            </a>

            <a href="/admin/users.php">
                User
            </a>

            <a href="/admin/obat.php">
                Obat
            </a>

            <a href="/admin/pesanan.php">
                Pesanan
            </a>

            <a href="/admin/laporan.php">
                Laporan
            </a>

        <?php endif; ?>


        <!-- ================= APOTEKER ================= -->

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'apoteker'): ?>

            <a href="/apoteker/dashboard.php">
                Dashboard
            </a>

            <a href="/apoteker/resep.php">
                Resep
            </a>

            <a href="/apoteker/obat.php">
                Obat
            </a>

            <a href="/apoteker/konsultasi.php">
                Konsultasi
            </a>

        <?php endif; ?>


        <!-- ================= PELANGGAN ================= -->

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'pelanggan'): ?>

            <a href="/pelanggan/dashboard.php">
                Dashboard
            </a>

            <a href="/pelanggan/obat.php">
                Beli Obat
            </a>

            <a href="/pelanggan/pesanan.php">
                Pesanan Saya
            </a>

            <a href="/pelanggan/profil.php">
                Profil
            </a>

        <?php endif; ?>


        <!-- ================= USER INFO ================= -->

        <?php if(isset($_SESSION['nama'])): ?>

            <span class="user">

                👤
                <?= htmlspecialchars($_SESSION['nama']) ?>

                (<?= $_SESSION['role'] ?>)

            </span>

            <a
                href="/logout.php"
                class="logout"
            >
                Logout
            </a>

        <?php endif; ?>

    </nav>

</header>

<div class="container">

<?php

if (function_exists('flash_show')) {
    flash_show();
}

?>