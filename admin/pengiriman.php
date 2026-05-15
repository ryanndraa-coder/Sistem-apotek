<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('admin');
$pageTitle='Pengiriman';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)$_POST['id']; $s=$_POST['status_ngirim'];
    $st=$conn->prepare("UPDATE pengiriman SET status_ngirim=? WHERE id_pengiriman=?");
    $st->bind_param('si',$s,$id); $st->execute();
    flash('success','Status diupdate'); header('Location: pengiriman.php'); exit;
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Pengiriman</h1>
<div class="card">
<table><thead><tr><th>ID</th><th>Pesanan</th><th>Alamat</th><th>Ongkir</th><th>Status</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM pengiriman ORDER BY id_pengiriman DESC");
while($x=$r->fetch_assoc()): ?>
<tr><td>#<?=$x['id_pengiriman']?></td><td>#<?=$x['id_pesanan']?></td>
<td><?=htmlspecialchars($x['alamat_ngirim'])?></td><td>Rp <?=number_format($x['ongkir'],0,',','.')?></td>
<td>
<form method="post" style="display:flex;gap:6px">
<input type="hidden" name="id" value="<?=$x['id_pengiriman']?>">
<select name="status_ngirim">
<?php foreach(['diproses','dikirim','terkirim','gagal'] as $s):?><option <?=$x['status_ngirim']===$s?'selected':''?>><?=$s?></option><?php endforeach;?>
</select><button class="btn btn-sm">✓</button>
</form>
</td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
