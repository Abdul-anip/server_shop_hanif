<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    define('DB_HOST', 'sql109.infinityfree.com');
    define('DB_USER', 'if0_40858461');
    define('DB_NAME', 'if0_40858461_product_items'); 
    define('DB_PASS', 'IujSengHMvrIH82'); 

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>