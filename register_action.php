<?php
require 'db.php'; // Connexion

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Sécurise le mot de passe

try {
    $stmt = $pdo->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);
    echo "Registration successful!";
    // Tu peux rediriger vers login.html par exemple
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
