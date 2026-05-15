<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'apoteker') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Dashboard Apoteker 💊</h1>

<div class="stats">

    <div class="stat">
        <div class="label">Obat Tersedia</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Pesanan Hari Ini</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Konsultasi</div>
        <div class="value">0</div>
    </div>

</div>

<div class="card">

    <div class="toolbar">

        <h2>Menu Apoteker</h2>

    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">

        <a href="#" class="btn">
            💊 Data Obat
        </a>

        <a href="#" class="btn">
            📦 Pesanan
        </a>

        <a href="#" class="btn">
            💬 Konsultasi
        </a>

    </div>

</div>

<div class="card">

    <h2>Pesanan Terbaru</h2>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td colspan="3" style="text-align:center;">
                    Belum ada pesanan
                </td>
            </tr>

        </tbody>

    </table>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>