<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "LOGIN PAGE OK <br>";

session_start();
require_once __DIR__ . '/config/database.php';

echo "DATABASE CONNECTED <br>";