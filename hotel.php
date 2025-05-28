<?php
// Connexion à la base de données (à adapter si besoin)
$host = 'localhost';
$dbname = 'travel_booking';
$username = 'root';
$password = 'Shapy_tot1'; // ajoute le mot de passe s’il y en a un

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requête pour récupérer les hôtels + destination
$sql = "
SELECT h.name, h.stars, h.price_per_night, d.city, d.country
FROM hotel h
JOIN destination d ON h.destination_id = d.id
";

$stmt = $pdo->query($sql);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotels - EasyTravel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 40px;
      background-color: #f7f7f7;
    }

    h1 {
      text-align: center;
      margin-bottom: 40px;
      color: #333;
    }

    .hotel-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .hotel-card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .hotel-card h2 {
      margin: 0 0 10px;
      font-size: 1.3rem;
      color: #222;
    }

    .hotel-card p {
      margin: 5px 0;
      color: #555;
    }

    .stars {
      color: #f5a623;
    }
  </style>
</head>
<body>

<h1>Available Hotels</h1>

<div class="hotel-list">
  <?php foreach ($hotels as $hotel): ?>
    <div class="hotel-card">
      <h2><?= htmlspecialchars($hotel['name']) ?></h2>
      <p><strong>Location:</strong> <?= htmlspecialchars($hotel['city']) ?>, <?= htmlspecialchars($hotel['country']) ?></p>
      <p><strong>Price:</strong> €<?= htmlspecialchars($hotel['price_per_night']) ?>/night</p>
      <p><strong>Stars:</strong> <span class="stars"><?= str_repeat('★', $hotel['stars']) ?></span></p>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
