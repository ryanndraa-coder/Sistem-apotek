<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
login_required('admin');
$pageTitle='Manajemen User';

if (($_GET['action']??'')==='delete' && isset($_GET['id'])) {
    $id=(int)$_GET['id'];
    if ($id != $_SESSION['user']['id_user']) {
        $conn->query("DELETE FROM user WHERE id_user=$id");
        flash('success','User dihapus');
    } else flash('error','Tidak bisa hapus akun sendiri');
    header('Location: users.php'); exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nama=$_POST['nama']; $email=$_POST['email']; $role=$_POST['role'];
    $id=(int)($_POST['id']??0);
    if ($id) {
        $s=$conn->prepare("UPDATE user SET nama=?,email=?,role=? WHERE id_user=?");
        $s->bind_param('sssi',$nama,$email,$role,$id); $s->execute();
        flash('success','User diupdate');
    } else {
        $pass=password_hash($_POST['password']?:'password123',PASSWORD_BCRYPT);
        $s=$conn->prepare("INSERT INTO user(nama,email,password,role) VALUES(?,?,?,?)");
        $s->bind_param('ssss',$nama,$email,$pass,$role); $s->execute();
        $nid=$conn->insert_id;
        $conn->query("INSERT INTO {$role}(id_user) VALUES($nid)");
        flash('success','User ditambahkan');
    }
    header('Location: users.php'); exit;
}
$edit=null;
if (($_GET['action']??'')==='edit' && isset($_GET['id'])) {
    $r=$conn->query("SELECT * FROM user WHERE id_user=".(int)$_GET['id']);
    $edit=$r->fetch_assoc();
}
include __DIR__ . '/../includes/header.php';
?>
<h1>Manajemen User</h1>
<div class="card">
  <h2><?=$edit?'Edit User':'Tambah User'?></h2>
  <form method="post" class="form-stack">
    <input type="hidden" name="id" value="<?=$edit['id_user']??''?>">
    <div><label>Nama</label><input name="nama" value="<?=htmlspecialchars($edit['nama']??'')?>" required></div>
    <div><label>Email</label><input type="email" name="email" value="<?=htmlspecialchars($edit['email']??'')?>" required></div>
    <?php if(!$edit):?><div><label>Password (default: password123)</label><input type="password" name="password"></div><?php endif;?>
    <div><label>Role</label><select name="role" required>
      <?php foreach(['admin','apoteker','pelanggan'] as $r):?>
        <option value="<?=$r?>" <?=($edit['role']??'')===$r?'selected':''?>><?=$r?></option>
      <?php endforeach;?>
    </select></div>
    <div><button class="btn"><?=$edit?'Update':'Tambah'?></button>
    <?php if($edit):?><a class="btn btn-secondary" href="users.php">Batal</a><?php endif;?></div>
  </form>
</div>
<div class="card">
<h2>Daftar User</h2>
<table><thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead><tbody>
<?php $r=$conn->query("SELECT * FROM user ORDER BY id_user");
while($u=$r->fetch_assoc()): ?>
<tr><td><?=$u['id_user']?></td><td><?=htmlspecialchars($u['nama'])?></td>
<td><?=htmlspecialchars($u['email'])?></td>
<td><span class="badge blue"><?=$u['role']?></span></td>
<td>
<a class="btn btn-sm btn-warning" href="?action=edit&id=<?=$u['id_user']?>">Edit</a>
<a class="btn btn-sm btn-danger" href="?action=delete&id=<?=$u['id_user']?>" onclick="return confirm('Hapus user?')">Hapus</a>
</td></tr>
<?php endwhile;?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
