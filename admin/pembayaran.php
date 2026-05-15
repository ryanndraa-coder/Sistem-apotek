<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('admin');
$pageTitle='Pembayaran';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)$_POST['id']; $s=$_POST['status_bayar'];
    $st=$conn->prepare("UPDATE pembayaran SET status_bayar=? WHERE id_pembayaran=?");
    $st->bind_param('si',$s,$id); $st->execute();
    flash('success','Status diupdate'); header('Location: pembayaran.php'); exit;
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Pembayaran</h1>
<div class="card">
<table><thead><tr><th>ID</th><th>Pesanan</th><th>Metode</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT pb.*,u.nama FROM pembayaran pb JOIN pesanan p ON pb.id_pesanan=p.id_pesanan JOIN user u ON p.id_user_pelanggan=u.id_user ORDER BY pb.id_pembayaran DESC");
while($x=$r->fetch_assoc()): ?>
<tr><td>#<?=$x['id_pembayaran']?></td><td>#<?=$x['id_pesanan']?> (<?=htmlspecialchars($x['nama'])?>)</td>
<td><?=htmlspecialchars($x['metode'])?></td><td><?=$x['tanggal_bayar']?></td>
<td>
<form method="post" style="display:flex;gap:6px">
<input type="hidden" name="id" value="<?=$x['id_pembayaran']?>">
<select name="status_bayar">
<?php foreach(['belum','lunas','gagal'] as $s):?><option <?=$x['status_bayar']===$s?'selected':''?>><?=$s?></option><?php endforeach;?>
</select><button class="btn btn-sm">✓</button>
</form>
</td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
