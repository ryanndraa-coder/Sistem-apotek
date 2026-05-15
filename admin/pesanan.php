<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('admin');
$pageTitle='Pesanan';

if (($_GET['action']??'')==='delete') { $conn->query("DELETE FROM pesanan WHERE id_pesanan=".(int)$_GET['id']); flash('success','Dihapus'); header('Location: pesanan.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_status'])) {
    $id=(int)$_POST['id']; $s=$_POST['status_pesanan'];
    $st=$conn->prepare("UPDATE pesanan SET status_pesanan=? WHERE id_pesanan=?");
    $st->bind_param('si',$s,$id); $st->execute();
    flash('success','Status diupdate'); header('Location: pesanan.php'); exit;
}
$detailId = isset($_GET['detail'])?(int)$_GET['detail']:0;
include __DIR__ . '/../includes/header.php';
?>
<h1>Manajemen Pesanan</h1>
<?php if($detailId): 
  $p=$conn->query("SELECT p.*,u.nama FROM pesanan p JOIN user u ON p.id_user_pelanggan=u.id_user WHERE id_pesanan=$detailId")->fetch_assoc();
?>
<div class="card">
  <h2>Detail Pesanan #<?=$p['id_pesanan']?></h2>
  <p><b>Pelanggan:</b> <?=htmlspecialchars($p['nama'])?> | <b>Tanggal:</b> <?=$p['tanggal_pesanan']?> | <b>Status:</b> <?=$p['status_pesanan']?></p>
  <table><thead><tr><th>Obat</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead><tbody>
  <?php $d=$conn->query("SELECT o.nama_obat,o.harga,dp.jumlah FROM detail_pesanan dp JOIN obat o ON dp.id_obat=o.id_obat WHERE dp.id_pesanan=$detailId");
  $tot=0; while($x=$d->fetch_assoc()): $sub=$x['harga']*$x['jumlah']; $tot+=$sub; ?>
  <tr><td><?=htmlspecialchars($x['nama_obat'])?></td><td>Rp <?=number_format($x['harga'],0,',','.')?></td>
  <td><?=$x['jumlah']?></td><td>Rp <?=number_format($sub,0,',','.')?></td></tr>
  <?php endwhile;?>
  <tr><td colspan="3" style="text-align:right"><b>Total</b></td><td><b>Rp <?=number_format($tot,0,',','.')?></b></td></tr>
  </tbody></table>
  <p style="margin-top:14px"><a class="btn btn-secondary" href="pesanan.php">← Kembali</a></p>
</div>
<?php else: ?>
<div class="card">
<table><thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT p.*,u.nama FROM pesanan p JOIN user u ON p.id_user_pelanggan=u.id_user ORDER BY p.id_pesanan DESC");
while($p=$r->fetch_assoc()): ?>
<tr><td>#<?=$p['id_pesanan']?></td><td><?=$p['tanggal_pesanan']?></td><td><?=htmlspecialchars($p['nama'])?></td>
<td>
  <form method="post" style="display:flex;gap:6px">
    <input type="hidden" name="id" value="<?=$p['id_pesanan']?>">
    <select name="status_pesanan">
      <?php foreach(['pending','diproses','dikirim','selesai','batal'] as $s):?>
      <option <?=$p['status_pesanan']===$s?'selected':''?>><?=$s?></option>
      <?php endforeach;?>
    </select>
    <button class="btn btn-sm" name="update_status" value="1">✓</button>
  </form>
</td>
<td>
<a class="btn btn-sm" href="?detail=<?=$p['id_pesanan']?>">Detail</a>
<a class="btn btn-sm btn-danger" href="?action=delete&id=<?=$p['id_pesanan']?>" onclick="return confirm('Hapus?')">Hapus</a>
</td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
