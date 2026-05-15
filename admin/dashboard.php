<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

login_required('admin');

$pageTitle = 'Dashboard Admin';

$id = $_SESSION['id_user'];

$stats = [

    'Users' => $conn->query("
        SELECT COUNT(*) c
        FROM user
    ")->fetch_assoc()['c'],

    'Obat' => $conn->query("
        SELECT COUNT(*) c
        FROM obat
    ")->fetch_assoc()['c'],

    'Pesanan' => $conn->query("
        SELECT COUNT(*) c
        FROM pesanan
    ")->fetch_assoc()['c'],

    'Pelanggan' => $conn->query("
        SELECT COUNT(*) c
        FROM pelanggan
    ")->fetch_assoc()['c'],

    'Apoteker' => $conn->query("
        SELECT COUNT(*) c
        FROM apoteker
    ")->fetch_assoc()['c'],
];

include __DIR__ . '/../includes/header.php';
?>

<h1>Dashboard Admin</h1>

<div class="stats">

<?php foreach($stats as $k => $v): ?>

<div class="stat">

<div class="label">
<?=$k?>
</div>

<div class="value">
<?=$v?>
</div>

</div>

<?php endforeach; ?>

</div>

<div class="card">

<h2>Pesanan Terbaru</h2>

<table>

<thead>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Pelanggan</th>
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
LIMIT 8
");

while($row = $r->fetch_assoc()):
?>

<tr>

<td>
#<?=$row['id_pesanan']?>
</td>

<td>
<?=$row['tanggal_pesanan']?>
</td>

<td>
<?=htmlspecialchars($row['nama'])?>
</td>

<td>
<?=$row['status_pesanan']?>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>