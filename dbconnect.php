<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// Ambil ENV dari Railway
$host = getenv('mysql.railway.internal');
$user = getenv('root');
$pass = getenv('dZLGtARjoiHrvpRquezetOaogSeFDvKq');
$name = getenv('railway');
$port = getenv('3306') ?: 3306;

// Validasi ENV
if (!$host || !$user || !$name) {
    die("Environment database belum lengkap");
}

$conn = new mysqli($host, $user, $pass, $name, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
?>
