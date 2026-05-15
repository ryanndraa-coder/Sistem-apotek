<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

login_required('apoteker');

/* TAMBAHAN SESSION */
if(!isset($_SESSION['id_user'])){

    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] !== 'apoteker'){

    header("Location: ../login.php");
    exit;
}
/* ================= */

$pageTitle='Dashboard Apoteker';
$id = $_SESSION['id_user'];

$jk = $conn->query("SELECT COUNT(*) c FROM konsultasi 
WHERE id_user_apoteker=$id")->fetch_assoc()['c'];

$jo = $conn->query("SELECT COUNT(*) c FROM obat 
WHERE status_obat='tersedia'")->fetch_assoc()['c'];

include __DIR__ . '/../includes/header.php';
?>

<h1>Dashboard Apoteker</h1>

<div class="stats">

<div class="stat">
    <div class="label">Konsultasi Saya</div>
    <div class="value"><?=$jk?></div>
</div>

<div class="stat">
    <div class="label">Obat Tersedia</div>
    <div class="value"><?=$jo?></div>
</div>

</div>

<div class="card">

<h2>Konsultasi Terbaru</h2>

<table>

<thead>
<tr>
<th>Tanggal</th>
<th>Pelanggan</th>
<th>Catatan</th>
</tr>
</thead>

<tbody>

<?php

$r = $conn->query("
SELECT k.*,u.nama 
FROM konsultasi k 
JOIN user u 
ON k.id_user_pelanggan=u.id_user 
WHERE k.id_user_apoteker=$id 
ORDER BY k.id_konsultasi DESC 
LIMIT 5
");

while($x=$r->fetch_assoc()):
?>

<tr>
<td><?=$x['tanggal']?></td>
<td><?=htmlspecialchars($x['nama'])?></td>
<td><?=htmlspecialchars($x['catatan'])?></td>
</tr>

<?php endwhile;?>

</tbody>

</table>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>