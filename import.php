<?php

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = file_get_contents(__DIR__ . '/sql/apotek.sql');

if ($conn->multi_query($sql)) {
    echo "Import database berhasil!";
} else {
    echo "Error: " . $conn->error;
}
?>