<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$port = getenv('MYSQLPORT');

$db = getenv('MYSQLDATABASE');

$conn = @new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Database Error: " . $conn->connect_error);
}
?>