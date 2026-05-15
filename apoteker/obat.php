<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('apoteker');
$pageTitle='Daftar Obat';
include __DIR__ . '/../includes/header.php';
?>
<h1>Daftar Obat</h1>
<div class="card">
<table><thead><tr><th>ID</th><th>Nama</th><th>Jenis</th><th>Harga</th><th>Stok</th><th>Kadaluarsa</th><th>Status</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM obat ORDER BY nama_obat");
while($o=$r->fetch_assoc()): ?>
<tr><td><?=$o['id_obat']?></td><td><?=htmlspecialchars($o['nama_obat'])?></td>
<td><?=htmlspecialchars($o['jenis_obat'])?></td><td>Rp <?=number_format($o['harga'],0,',','.')?></td>
<td><?=$o['stok']?></td><td><?=$o['tanggal_kadaluarsa']?></td>
<td><span class="badge <?=$o['status_obat']==='tersedia'?'green':'red'?>"><?=$o['status_obat']?></span></td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
