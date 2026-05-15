<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // Cek email
    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {

            // Simpan session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {

                header("Location: /apotek-web/admin/dashboard.php");
                exit;

            } elseif ($user['role'] === 'apoteker') {

                header("Location: /apotek-web/apoteker/dashboard.php");
                exit;

            } elseif ($user['role'] === 'pelanggan') {

                header("Location: /apotek-web/pelanggan/dashboard.php");
                exit;

            } else {

                $error = "Role tidak dikenali!";
            }

        } else {

            $error = "Password salah!";
        }

    } else {

        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="login-wrap">

    <div class="login-card">

        <h1>Login</h1>

        <p class="sub">
            Masuk ke Sistem Apotek
        </p>

        <?php if($error): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="form-stack">

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
                >

            </div>

            <button class="btn" type="submit">
                Login
            </button>

        </form>

        <p style="text-align:center;margin-top:14px;font-size:.9rem">

            Belum punya akun?

            <a class="link" href="register.php">
                Daftar
            </a>

        </p>

    </div>

</div>

</body>
</html>