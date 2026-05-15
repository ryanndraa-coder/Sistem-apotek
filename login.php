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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Apotek</title>
</head>
<body>

<h2>Login Sistem Apotek</h2>

<?php if($error): ?>
    <p style="color:red;">
        <?= $error ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>