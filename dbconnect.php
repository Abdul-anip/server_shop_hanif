<?php
    header("Access-Control-Allow-Origin: *");
    
    // Railway otomatis menyediakan variabel ini
    $host = getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'mysql.railway.internal';
    $user = getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'root';
    $pass = getenv('MYSQLPASSWORD') ? getenv('MYSQLPASSWORD') : 'dZLGtARjoiHrvpRquezetOaogSeFDvKq';
    $name = getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'railway';
    $port = getenv('MYSQLPORT') ? getenv('MYSQLPORT') : 3306;
    
    $conn = new mysqli($host, $user, $pass, $name, $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>