# Full Tampilan Admin Sistem Apotek

## admin/dashboard.php

```php
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

$pageTitle = 'Dashboard Admin';

require_once __DIR__ . '/../includes/header.php';

$totalUser = $conn->query("SELECT COUNT(*) total FROM user")
->fetch_assoc()['total'];

$totalObat = $conn->query("SELECT COUNT(*) total FROM obat")
->fetch_assoc()['total'];

$totalPesanan = $conn->query("SELECT COUNT(*) total FROM pesanan")
->fetch_assoc()['total'];

$totalApoteker = $conn->query("SELECT COUNT(*) total FROM user WHERE role='apoteker'")
->fetch_assoc()['total'];

?>

<div class="toolbar">

    <div>
        <h1>👨‍💼 Dashboard Admin</h1>

        <p style="color:#64748b">
            Selamat datang,
            <?= htmlspecialchars($_SESSION['nama']) ?>
        </p>
    </div>

</div>

<div class="stats">

    <div class="stat">
        <div class="label">👥 Total User</div>
        <div class="value"><?= $totalUser ?></div>
    </div>

    <div class="stat">
        <div class="label">💊 Total Obat</div>
        <div class="value"><?= $totalObat ?></div>
    </div>

    <div class="stat">
        <div class="label">📦 Total Pesanan</div>
        <div class="value"><?= $totalPesanan ?></div>
    </div>

    <div class="stat">
        <div class="label">🧑‍⚕️ Total Apoteker</div>
        <div class="value"><?= $totalApoteker ?></div>
    </div>

</div>

<div class="card">

    <div class="toolbar">

        <h2>⚙️ Menu Admin</h2>

    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">

        <a href="users.php" class="btn" style="padding:18px;text-align:center">
            👥 Kelola User
        </a>

        <a href="obat.php" class="btn" style="padding:18px;text-align:center">
            💊 Kelola Obat
        </a>

        <a href="pesanan.php" class="btn" style="padding:18px;text-align:center">
            📦 Kelola Pesanan
        </a>

        <a href="laporan.php" class="btn" style="padding:18px;text-align:center">
            📊 Laporan
        </a>

    </div>

</div>

<div class="card">

    <div class="toolbar">
        <h2>📋 Pesanan Terbaru</h2>
    </div>

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
            <td colspan="4" style="text-align:center">
                Belum ada pesanan
            </td>
        </tr>

        <?php endif; ?>

        <?php while($x = $r->fetch_assoc()): ?>

        <tr>

            <td>#<?= $x['id_pesanan'] ?></td>

            <td><?= htmlspecialchars($x['nama']) ?></td>

            <td><?= $x['tanggal_pesanan'] ?></td>

            <td>

                <span class="badge <?= $x['status_pesanan']=='selesai' ? 'green' : 'yellow' ?>">
                    <?= $x['status_pesanan'] ?>
                </span>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
```

---

## Tambahkan menu admin di includes/header.php

Cari bagian navbar admin lalu ganti menjadi:

```php
<?php if($_SESSION['role']=='admin'): ?>

<a href="/admin/dashboard.php">Dashboard</a>
<a href="/admin/users.php">User</a>
<a href="/admin/obat.php">Obat</a>
<a href="/admin/pesanan.php">Pesanan</a>
<a href="/admin/laporan.php">Laporan</a>

<?php endif; ?>
```

---

## Tambahkan style modern di assets/css/style.css

Tambahkan paling bawah:

```css
.sidebar-card{
background:#fff;
border-radius:14px;
padding:18px;
box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.menu-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:16px;
}

.menu-box{
background:#0d9488;
color:#fff;
padding:22px;
border-radius:14px;
text-decoration:none;
font-weight:600;
transition:.2s;
text-align:center;
}

.menu-box:hover{
transform:translateY(-3px);
background:#0f766e;
}
```

---

## Git Push

```bash
git add .

git commit -m "upgrade full admin ui"

git push
```
