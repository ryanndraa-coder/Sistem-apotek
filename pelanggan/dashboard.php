<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">

    <h1>
        Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋
    </h1>

    <p>
        Selamat datang di Sistem Apotek Sehat
    </p>

</div>

<div class="stats">

    <div class="stat">
        <div class="label">🛒 Total Pesanan</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">💬 Konsultasi</div>
        <div class="value">0</div>
    </div>

    <div class="stat">
        <div class="label">👤 Status</div>
        <div class="value" style="font-size:1rem;">
            Pelanggan
        </div>
    </div>

</div>

<div class="card">

    <div class="toolbar">

        <h2>Menu Cepat</h2>

    </div>

    <div
        style="
            display:grid;
            grid-template-columns:
            repeat(auto-fit,minmax(200px,1fr));
            gap:16px;
        "
    >

        <a
            href="beli_obat.php"
            class="btn"
            style="
                padding:20px;
                text-align:center;
                font-size:1rem;
            "
        >
            🛒 Beli Obat
        </a>

        <a
            href="#"
            class="btn btn-secondary"
            style="
                padding:20px;
                text-align:center;
                font-size:1rem;
            "
        >
            📦 Pesanan Saya
        </a>

        <a
            href="#"
            class="btn"
            style="
                padding:20px;
                text-align:center;
                font-size:1rem;
            "
        >
            💬 Konsultasi
        </a>

    </div>

</div>

<div class="card">

    <h2>Riwayat Pesanan</h2>

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