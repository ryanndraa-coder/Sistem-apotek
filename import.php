<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

echo "HOST: $host <br>";
echo "DB: $db <br>";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Koneksi database berhasil!<br>";

$sqlFile = __DIR__ . '/sql/apotek.sql';

if (!file_exists($sqlFile)) {
    die("File SQL tidak ditemukan: " . $sqlFile);
}

$sql = file_get_contents($sqlFile);

if ($conn->multi_query($sql)) {
    echo "Import database berhasil!";
} else {
    echo "Error import: " . $conn->error;
}
?>