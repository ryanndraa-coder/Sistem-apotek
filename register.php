<?php 
require_once __DIR__ . '/config/database.php'; 
require_once __DIR__ . '/includes/auth.php'; 

$error=''; 
$success=''; 

if ($_SERVER['REQUEST_METHOD']==='POST') { 
    $nama = trim($_POST['nama']); 
    $email= trim($_POST['email']); 
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $telp = trim($_POST['no_telp']);
    // Mengambil role dari input form
    $role = $_POST['role']; 

    $check= $conn->prepare("SELECT id_user FROM user WHERE email=?"); 
    $check->bind_param('s',$email); 
    $check->execute(); 

    if ($check->get_result()->num_rows>0) { 
        $error='Email sudah terdaftar'; 
    } else { 
        $conn->begin_transaction(); 
        try { 
            // Query INSERT diubah agar 'role' bersifat dinamis sesuai pilihan user
            $s=$conn->prepare("INSERT INTO user(nama, email, password, role) VALUES(?, ?, ?, ?)"); 
            $s->bind_param('ssss', $nama, $email, $pass, $role); 
            $s->execute(); 
            
            $id=$conn->insert_id; 

            // Logika tambahan: jika role adalah pelanggan, masukkan ke tabel pelanggan
            if ($role === 'pelanggan') {
                $conn->query("INSERT INTO pelanggan(id_user) VALUES($id)"); 
                if ($telp) { 
                    $t=$conn->prepare("INSERT INTO pelanggan_no_telp(no_telp, id_user_pelanggan) VALUES(?, ?)"); 
                    $t->bind_param('si', $telp, $id); 
                    $t->execute(); 
                }
            }

            $conn->commit(); 
            $success='Pendaftaran berhasil sebagai ' . ucfirst($role) . '! Silakan login.'; 
        } catch(Exception $e){ 
            $conn->rollback(); 
            $error='Gagal daftar: '.$e->getMessage(); 
        } 
    } 
} 
?> 

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title> 
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body> 
<div class="login-wrap">
    <div class="login-card"> 
        <h1>Daftar Akun</h1>
        <p class="sub">Pilih peran dan buat akun baru</p> 

        <?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?> 
        <?php if($success):?><div class="alert success"><?=$success?></div><?php endif;?> 

        <form method="post" class="form-stack"> 
            <div><label>Nama</label><input name="nama" required></div> 
            <div><label>Email</label><input type="email" name="email" required></div> 
            <div><label>Password</label><input type="password" name="password" required minlength="6"></div> 
            
            <!-- Tambahan pilihan Role -->
            <div>
                <label>Daftar Sebagai</label>
                <select name="role" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="pelanggan">Pelanggan</option>
                    <option value="apoteker">Apoteker</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div><label>No. Telp (Khusus Pelanggan)</label><input name="no_telp" placeholder="08xxx"></div> 
            
            <button class="btn">Daftar</button> 
        </form> 
        <p style="text-align:center;margin-top:14px;font-size:.9rem">Sudah punya akun? <a class="link" href="login.php">Login</a></p> 
    </div>
</div>
</body>
</html>
