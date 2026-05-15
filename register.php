<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $no_telp  = trim($_POST['no_telp']);
    $role     = $_POST['role'];

    // validasi role
    $allowedRoles = ['pelanggan', 'admin', 'apoteker'];

    if (!in_array($role, $allowedRoles)) {

        $error = "Role tidak valid!";

    } else {

        // cek email
        $check = $conn->prepare("
            SELECT id_user
            FROM user
            WHERE email = ?
        ");

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Email sudah terdaftar!";

        } else {

            $hashPassword = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            $conn->begin_transaction();

            try {

                // insert user
                $stmt = $conn->prepare("
                    INSERT INTO user
                    (nama, email, password, role)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "ssss",
                    $nama,
                    $email,
                    $hashPassword,
                    $role
                );

                $stmt->execute();

                $id_user = $conn->insert_id;

                // jika pelanggan
                if ($role === 'pelanggan') {

                    $pelanggan = $conn->prepare("
                        INSERT INTO pelanggan(id_user)
                        VALUES(?)
                    ");

                    $pelanggan->bind_param(
                        "i",
                        $id_user
                    );

                    $pelanggan->execute();

                    // no telp pelanggan
                    if (!empty($no_telp)) {

                        $telp = $conn->prepare("
                            INSERT INTO pelanggan_no_telp
                            (no_telp, id_user_pelanggan)
                            VALUES(?, ?)
                        ");

                        $telp->bind_param(
                            "si",
                            $no_telp,
                            $id_user
                        );

                        $telp->execute();
                    }
                }

                $conn->commit();

                $success = "Pendaftaran berhasil sebagai " . ucfirst($role);

            } catch (Exception $e) {

                $conn->rollback();

                $error = "Pendaftaran gagal!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Akun</title>

    <link
        rel="stylesheet"
        href="/assets/css/style.css"
    >

</head>

<body>

<div class="login-wrap">

    <div class="login-card">

        <h1>Daftar Akun</h1>

        <p class="sub">
            Buat akun baru Sistem Apotek
        </p>

        <?php if($error): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if($success): ?>

            <div class="alert success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="form-stack">

            <div>

                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="nama"
                    required
                >

            </div>

            <div>

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    required
                >

            </div>

            <div>

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                    minlength="6"
                >

            </div>

            <div>

                <label>No. Telepon</label>

                <input
                    type="text"
                    name="no_telp"
                    placeholder="08xxxxxxxxxx"
                >

            </div>

            <!-- pilihan role -->

            <div>

                <label>Daftar Sebagai</label>

                <select name="role" required>

                    <option value="pelanggan">
                        Pelanggan
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                    <option value="apoteker">
                        Apoteker
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn"
            >
                Daftar
            </button>

        </form>

        <p
            style="
                text-align:center;
                margin-top:15px;
            "
        >

            Sudah punya akun?

            <a
                href="login.php"
                class="link"
            >
                Login
            </a>

        </p>

    </div>

</div>

</body>
</html>