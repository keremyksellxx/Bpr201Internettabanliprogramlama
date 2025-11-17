<?php

$host = "localhost";
$dbname = "haber_portali";
$username = "root";   
$password = "";      

try {
    // PDO bağlantısı
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // PDO hata modu
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
