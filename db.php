<?php
$host = 'localhost';
$db   = 'travel_booking';
$user = 'root';
$pass = '0312bagueraT!';  // Laisser vide si pas de mot de passe (ou mets le tien ici)
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    // Active les erreurs PDO en mode exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
