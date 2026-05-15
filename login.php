<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "STEP 1<br>";

session_start();

echo "STEP 2<br>";

require_once __DIR__ . '/config/database.php';

echo "STEP 3 DATABASE CONNECTED<br>";

$query = $conn->query("SELECT * FROM user");

echo "STEP 4 QUERY OK<br>";

$data = $query->fetch_assoc();

echo "<pre>";
print_r($data);
echo "</pre>";