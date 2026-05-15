<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Apotek Sehat</title>

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

        <a href="/pelanggan/dashboard.php">
            Dashboard
        </a>

        <a href="#">
            Beli Obat
        </a>

        <a href="#">
            Pesanan Saya
        </a>

        <a href="#">
            Profil
        </a>

        <span class="user">
            👤 <?= $_SESSION['nama'] ?>
            (<?= $_SESSION['role'] ?>)
        </span>

        <a href="/logout.php" class="logout">
            Logout
        </a>

    </nav>

</header>

<div class="container">