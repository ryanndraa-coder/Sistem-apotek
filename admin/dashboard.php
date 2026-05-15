<?php

session_start();

require_once __DIR__ . '/../config/database.php';

if (
    !isset($_SESSION['id_user']) ||
    $_SESSION['role'] !== 'admin'
) {

    header("Location: ../login.php");
    exit;
}

$pageTitle = "Dashboard Admin";

require_once __DIR__ . '/../includes/header.php';

// total user
$totalUser = $conn->query("
    SELECT COUNT(*) as total
    FROM user
")->fetch_assoc()['total'];

// total obat
$totalObat = $conn->query("
    SELECT COUNT(*) as total
    FROM obat
")->fetch_assoc()['total'];

// total pesanan
$totalPesanan = $conn->query("
    SELECT COUNT(*) as total
    FROM pesanan
")->fetch_assoc()['total'];

// total pelanggan
$totalPelanggan = $conn->query("
    SELECT COUNT(*) as total
    FROM user
    WHERE role='pelanggan'
")->fetch_assoc()['total'];

?>

<h1>👨‍💼 Dashboard Admin</h1>

<p
    style="
        margin-bottom:20px;
        color:#64748b;
    "
>
    Selamat datang,
    <?= htmlspecialchars($_SESSION['nama']) ?>
</p>

<div class="stats">

    <div class="stat">

        <div class="label">
            👥 Total User
        </div>

        <div class="value">
            <?= $totalUser ?>
        </div>

    </div>

    <div class="stat">

        <div class="label">
            💊 Total Obat
        </div>

        <div class="value">
            <?= $totalObat ?>
        </div>

    </div>

    <div class="stat">

        <div class="label">
            📦 Total Pesanan
        </div>

        <div class="value">
            <?= $totalPesanan ?>
        </div>

    </div>

    <div class="stat">

        <div class="label">
            🧑 Pelanggan
        </div>

        <div class="value">
            <?= $totalPelanggan ?>
        </div>

    </div>

</div>

<div class="card">

    <div class="toolbar">

        <h2>⚙️ Menu Admin</h2>

    </div>

    <div
        style="
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        "
    >

        <a
            href="users.php"
            class="btn"
        >
            👥 Kelola User
        </a>

        <a
            href="obat.php"
            class="btn"
        >
            💊 Kelola Obat
        </a>

        <a
            href="pesanan.php"
            class="btn"
        >
            📦 Kelola Pesanan
        </a>

        <a
            href="laporan.php"
            class="btn"
        >
            📊 Laporan
        </a>

    </div>

</div>

<div class="card">

    <h2>📋 Pesanan Terbaru</h2>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Status</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $r = $conn->query("
            SELECT
                p.id_pesanan,
                p.tanggal_pesanan,
                p.status_pesanan,
                u.nama
            FROM pesanan p
            JOIN user u
            ON p.id_user_pelanggan = u.id_user
            ORDER BY p.id_pesanan DESC
            LIMIT 5
        ");

        if($r->num_rows == 0):
        ?>

        <tr>

            <td
                colspan="4"
                style="text-align:center"
            >
                Belum ada pesanan
            </td>

        </tr>

        <?php endif; ?>

        <?php while($x = $r->fetch_assoc()): ?>

        <tr>

            <td>
                #<?= $x['id_pesanan'] ?>
            </td>

            <td>
                <?= htmlspecialchars($x['nama']) ?>
            </td>

            <td>
                <?= $x['tanggal_pesanan'] ?>
            </td>

            <td>

                <span
                    class="badge <?= $x['status_pesanan']=='selesai'
                        ? 'green'
                        : 'yellow'
                    ?>"
                >
                    <?= $x['status_pesanan'] ?>
                </span>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<div class="card">

    <h2>📈 Aktivitas Sistem</h2>

    <table>

        <thead>

            <tr>

                <th>Keterangan</th>
                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>
                    Sistem Login
                </td>

                <td>
                    <span class="badge green">
                        Aktif
                    </span>
                </td>

            </tr>

            <tr>

                <td>
                    Database MySQL
                </td>

                <td>
                    <span class="badge green">
                        Terhubung
                    </span>
                </td>

            </tr>

            <tr>

                <td>
                    Server Railway
                </td>

                <td>
                    <span class="badge green">
                        Online
                    </span>
                </td>

            </tr>

        </tbody>

    </table>

</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>