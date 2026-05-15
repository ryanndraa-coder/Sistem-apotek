<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h1>

<div class="stats">

    <div class="stat">
        <div class="label">Pesanan Saya</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Konsultasi</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">Status Akun</div>
        <div class="value" style="font-size:1rem;">
            Pelanggan
        </div>
    </div>

</div>

<div class="card">

    <div class="toolbar">
        <h2>Pesanan Terakhir</h2>

        <a href="beli_obat.php" class="btn">
            🛒 Beli Obat
        </a>
    </div>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
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