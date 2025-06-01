<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'travel_booking';
$user = 'root';
$password = 'Shapy_tot1'; // Mets ton mot de passe ici si besoin

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

/*What the file is doing:
> Displays 4-star and higher hotels (using the top_hotels view)
> llows the user to select a number of nights
> Uses the SQL function total_price_per_stay to calculate the total price
> Uses a form to dynamically update*/

// Récupérer le nombre de nuits depuis le formulaire, valeur par défaut : 3
$nights = isset($_GET['nights']) ? (int)$_GET['nights'] : 3;

// Récupérer les hôtels depuis la view
$stmt = $pdo->query("SELECT name, stars, price_per_night FROM top_hotels");
$hotels = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotels - EasyTravel</title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f7f7f7;
    }
    h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      margin-bottom: 20px;
    }
    .hotel {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .hotel h2 {
      margin-bottom: 10px;
    }
    .hotel p {
      margin: 5px 0;
    }
    form {
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <h1>Top Rated Hotels</h1>

  <form method="GET">
    <label for="nights">Number of nights:</label>
    <input type="number" name="nights" min="1" value="<?= htmlspecialchars($nights) ?>">
    <button type="submit">Update</button>
  </form>

  <?php foreach ($hotels as $hotel): 
    // Calcul du prix total via la fonction SQL
    $stmt = $pdo->prepare("SELECT total_price_per_stay(:price, :nights) AS total_price");
    $stmt->execute([
        'price' => $hotel['price_per_night'],
        'nights' => $nights
    ]);
    $result = $stmt->fetch();
    $total_price = $result['total_price'];
  ?>
    <div class="hotel">
      <h2><?= htmlspecialchars($hotel['name']) ?></h2>
      <p>⭐ <?= $hotel['stars'] ?> stars</p>
      <p>💰 Price per night: <?= number_format($hotel['price_per_night'], 2) ?> €</p>
      <p>🧾 Total for <?= $nights ?> nights: <strong><?= number_format($total_price, 2) ?> €</strong></p>
    </div>
  <?php endforeach; ?>
</body>
</html>
