<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('apoteker');
$pageTitle='Konsultasi';
$apo = $_SESSION['id_user'];


if (($_GET['action']??'')==='delete') { $conn->query("DELETE FROM konsultasi WHERE id_konsultasi=".(int)$_GET['id']); flash('success','Dihapus'); header('Location: konsultasi.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $tgl=$_POST['tanggal']; $cat=$_POST['catatan']; $pel=(int)$_POST['id_user_pelanggan'];
    $obats=$_POST['obat']??[];
    $s=$conn->prepare("INSERT INTO konsultasi(tanggal,catatan,id_user_apoteker,id_user_pelanggan) VALUES(?,?,?,?)");
    $s->bind_param('ssii',$tgl,$cat,$apo,$pel); 
    if (!$s->execute()) {
    die("Gagal insert: " . $s->error);
    }
    $kid=$conn->insert_id;
    foreach($obats as $oid) {
        $oid=(int)$oid;
        if($oid) $conn->query("INSERT INTO detail_konsultasi(id_konsultasi,id_obat) VALUES($kid,$oid)");
    }
    flash('success','Konsultasi disimpan'); header('Location: konsultasi.php'); exit;
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Konsultasi</h1>
<div class="card">
<h2>Tambah Konsultasi</h2>
<form method="post" class="form-stack">
  <div><label>Tanggal</label><input type="date" name="tanggal" value="<?=date('Y-m-d')?>" required></div>
  <div><label>Pelanggan</label><select name="id_user_pelanggan" required>
    <?php $p=$conn->query("SELECT u.id_user,u.nama FROM user u JOIN pelanggan pl ON u.id_user=pl.id_user");
    while($x=$p->fetch_assoc()):?><option value="<?=$x['id_user']?>"><?=htmlspecialchars($x['nama'])?></option><?php endwhile;?>
  </select></div>
  <div><label>Catatan</label><textarea name="catatan" rows="3" required></textarea></div>
  <div><label>Rekomendasi Obat (Ctrl+klik untuk pilih banyak)</label>
    <select name="obat[]" multiple size="5">
      <?php $o=$conn->query("SELECT id_obat,nama_obat FROM obat WHERE status_obat='tersedia'");
      while($x=$o->fetch_assoc()):?><option value="<?=$x['id_obat']?>"><?=htmlspecialchars($x['nama_obat'])?></option><?php endwhile;?>
    </select></div>
  <div><button class="btn">Simpan</button></div>
</form></div>
<div class="card"><h2>Riwayat Konsultasi Saya</h2>
<table><thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Catatan</th><th>Obat</th><th>Aksi</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT k.*,u.nama FROM konsultasi k JOIN user u ON k.id_user_pelanggan=u.id_user WHERE k.id_user_apoteker=$apo ORDER BY k.id_konsultasi DESC");
while($k=$r->fetch_assoc()):
  $obs=$conn->query("SELECT o.nama_obat FROM detail_konsultasi dk JOIN obat o ON dk.id_obat=o.id_obat WHERE dk.id_konsultasi={$k['id_konsultasi']}");
  $names=[]; while($x=$obs->fetch_assoc()) $names[]=$x['nama_obat'];
?>
<tr><td><?=$k['id_konsultasi']?></td><td><?=$k['tanggal']?></td><td><?=htmlspecialchars($k['nama'])?></td>
<td><?=htmlspecialchars($k['catatan'])?></td><td><?=htmlspecialchars(implode(', ',$names))?></td>
<td><a class="btn btn-sm btn-danger" href="?action=delete&id=<?=$k['id_konsultasi']?>" onclick="return confirm('Hapus?')">Hapus</a></td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
