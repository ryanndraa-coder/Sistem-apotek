<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Dashboard Admin 👨‍💼</h1>

<div class="stats">

    <div class="stat">
        <div class="label">Total User</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Total Obat</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Total Pesanan</div>
        <div class="value">0</div>
    </div>

</div>

<div class="card">

    <div class="toolbar">

        <h2>Menu Admin</h2>

    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">

        <a href="#" class="btn">
            👥 Kelola User
        </a>

        <a href="#" class="btn">
            💊 Kelola Obat
        </a>

        <a href="#" class="btn">
            📦 Kelola Pesanan
        </a>

        <a href="#" class="btn">
            📊 Laporan
        </a>

    </div>

</div>

<div class="card">

    <h2>Aktivitas Terbaru</h2>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Aktivitas</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td colspan="3" style="text-align:center;">
                    Belum ada aktivitas
                </td>
            </tr>

        </tbody>

    </table>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>