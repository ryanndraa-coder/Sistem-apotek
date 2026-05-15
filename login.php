<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {

                header("Location: admin/dashboard.php");
                exit;

            } elseif ($user['role'] === 'apoteker') {

                header("Location: apoteker/dashboard.php");
                exit;

            } elseif ($user['role'] === 'pelanggan') {

                header("Location: pelanggan/dashboard.php");
                exit;
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
<html>
<head>
    <title>Login Apotek</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<body>

<div class="login-wrap">

    <div class="login-card">

        <h1>Login Sistem Apotek</h1>

        <p class="sub">
            Silakan login untuk melanjutkan
        </p>

        <?php if($error): ?>
            <div class="alert error">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-stack">

            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn">
                Login
            </button>

        </form>

        <div class="demo">
            <b>Demo Login</b>
            admin@apotek.com <br>
            password123
        </div>

    </div>

</div>

</body>
</body>
</html>