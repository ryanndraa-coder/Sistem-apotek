<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('admin');
$pageTitle='Manajemen Obat';

if (($_GET['action']??'')==='delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM obat WHERE id_obat=".(int)$_GET['id']);
    flash('success','Obat dihapus'); header('Location: obat.php'); exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)($_POST['id']??0);
    $n=$_POST['nama_obat']; $j=$_POST['jenis_obat']; $h=(int)$_POST['harga'];
    $tk=$_POST['tanggal_kadaluarsa']; $st=$_POST['status_obat']; $sk=(int)$_POST['stok'];
    if ($id) {
        $s=$conn->prepare("UPDATE obat SET nama_obat=?,jenis_obat=?,harga=?,tanggal_kadaluarsa=?,status_obat=?,stok=? WHERE id_obat=?");
        $s->bind_param('ssisssi',$n,$j,$h,$tk,$st,$sk,$id);
    } else {
        $s=$conn->prepare("INSERT INTO obat(nama_obat,jenis_obat,harga,tanggal_kadaluarsa,status_obat,stok) VALUES(?,?,?,?,?,?)");
        $s->bind_param('ssissi',$n,$j,$h,$tk,$st,$sk);
    }
    $s->execute(); flash('success','Tersimpan'); header('Location: obat.php'); exit;
}
$edit=null;
if (($_GET['action']??'')==='edit') $edit=$conn->query("SELECT * FROM obat WHERE id_obat=".(int)$_GET['id'])->fetch_assoc();
include __DIR__ . '/../includes/header.php';
?>
<h1>Manajemen Obat</h1>
<div class="card">
<h2><?=$edit?'Edit':'Tambah'?> Obat</h2>
<form method="post" class="form-stack">
  <input type="hidden" name="id" value="<?=$edit['id_obat']??''?>">
  <div><label>Nama Obat</label><input name="nama_obat" value="<?=htmlspecialchars($edit['nama_obat']??'')?>" required></div>
  <div><label>Jenis</label><input name="jenis_obat" value="<?=htmlspecialchars($edit['jenis_obat']??'')?>" required></div>
  <div><label>Harga</label><input type="number" name="harga" value="<?=$edit['harga']??''?>" required></div>
  <div><label>Stok</label><input type="number" name="stok" value="<?=$edit['stok']??0?>" required></div>
  <div><label>Tanggal Kadaluarsa</label><input type="date" name="tanggal_kadaluarsa" value="<?=$edit['tanggal_kadaluarsa']??''?>" required></div>
  <div><label>Status</label><select name="status_obat">
    <?php foreach(['tersedia','habis','kadaluarsa'] as $s):?>
    <option value="<?=$s?>" <?=($edit['status_obat']??'')===$s?'selected':''?>><?=$s?></option>
    <?php endforeach;?>
  </select></div>
  <div><button class="btn"><?=$edit?'Update':'Tambah'?></button>
  <?php if($edit):?><a class="btn btn-secondary" href="obat.php">Batal</a><?php endif;?></div>
</form></div>
<div class="card"><h2>Daftar Obat</h2>
<table><thead><tr><th>ID</th><th>Nama</th><th>Jenis</th><th>Harga</th><th>Stok</th><th>Kadaluarsa</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM obat ORDER BY id_obat");
while($o=$r->fetch_assoc()): ?>
<tr><td><?=$o['id_obat']?></td><td><?=htmlspecialchars($o['nama_obat'])?></td>
<td><?=htmlspecialchars($o['jenis_obat'])?></td>
<td>Rp <?=number_format($o['harga'],0,',','.')?></td>
<td><?=$o['stok']?></td>
<td><?=$o['tanggal_kadaluarsa']?></td>
<td><span class="badge <?=$o['status_obat']==='tersedia'?'green':'red'?>"><?=$o['status_obat']?></span></td>
<td>
<a class="btn btn-sm btn-warning" href="?action=edit&id=<?=$o['id_obat']?>">Edit</a>
<a class="btn btn-sm btn-danger" href="?action=delete&id=<?=$o['id_obat']?>" onclick="return confirm('Hapus?')">Hapus</a>
</td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
