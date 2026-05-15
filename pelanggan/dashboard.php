<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

login_required('pelanggan');

/* VALIDASI SESSION */
if(!isset($_SESSION['id_user'])){

    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] !== 'pelanggan'){

    header("Location: ../login.php");
    exit;
}
/* ================= */

$pageTitle='Dashboard Pelanggan';

/* PERBAIKI SESSION */
$id = $_SESSION['id_user'];

$jp = $conn->query("
SELECT COUNT(*) c 
FROM pesanan 
WHERE id_user_pelanggan=$id
")->fetch_assoc()['c'];

$jk = $conn->query("
SELECT COUNT(*) c 
FROM konsultasi 
WHERE id_user_pelanggan=$id
")->fetch_assoc()['c'];

include __DIR__ . '/../includes/header.php';
?>

<h1>
Selamat datang, 
<?=htmlspecialchars($_SESSION['nama'])?>!
</h1>

<div class="stats">

<div class="stat">
    <div class="label">Pesanan Saya</div>
    <div class="value"><?=$jp?></div>
</div>

<div class="stat">
    <div class="label">Konsultasi</div>
    <div class="value"><?=$jk?></div>
</div>

</div>

<div class="card">

<h2>Pesanan Terakhir</h2>

<table>

<thead>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php

$r = $conn->query("
SELECT * 
FROM pesanan 
WHERE id_user_pelanggan=$id 
ORDER BY id_pesanan DESC 
LIMIT 5
");

while($x=$r->fetch_assoc()):
?>

<tr>
<td>#<?=$x['id_pesanan']?></td>
<td><?=$x['tanggal_pesanan']?></td>
<td>
<span class="badge blue">
<?=$x['status_pesanan']?>
</span>
</td>
</tr>

<?php endwhile;?>

</tbody>

</table>

<p style="margin-top:14px">
<a class="btn" href="obat.php">
🛒 Beli Obat
</a>
</p>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>