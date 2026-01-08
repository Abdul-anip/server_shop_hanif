<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$name = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: 3306;

if (!$host || !$user || !$name) {
    die("Environment database belum lengkap");
}

$conn = new mysqli($host, $user, $pass, $name, $port);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

echo "DB CONNECTED";
