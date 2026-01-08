<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    define('DB_HOST', 'mysql.railway.internal');
    define('DB_USER', 'root');
    define('DB_NAME', 'railway'); 
    define('DB_PASS', 'dZLGtARjoiHrvpRquezetOaogSeFDvKq'); 

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>