<?php
// File ini untuk mengisi database kosong di Railway secara otomatis
// Cukup buka file ini sekali di browser setelah di-deploy

include 'dbconnect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h3>Memulai Import Database...</h3>";

// Baca file SQL
$filename = 'product_items.sql';
if (!file_exists($filename)) {
    die("Error: File $filename tidak ditemukan.");
}

$sql = file_get_contents($filename);

// Eksekusi Query
if ($conn->multi_query($sql)) {
    do {
        // Habiskan result set agar tidak error
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "<h1>✅ Sukses! Database berhasil di-import.</h1>";
    echo "<p>Sekarang kamu bisa menghapus file ini dan buka <a href='allproduct.php'>allproduct.php</a></p>";
} else {
    echo "<h1>❌ Gagal!</h1>";
    echo "Error: " . $conn->error;
}
?>
