<?php
$results = [];
$error = '';

if (isset($_GET['city'])) {
    $city = trim($_GET['city']);

    if ($city !== '') {
        $host = 'localhost';
        $db   = 'travel_booking';
        $user = 'root';
        $pass = 'Shapy_tot1';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            $stmt = $pdo->prepare("
                SELECT 
                    Flight.airline, 
                    Flight.departure_date, 
                    Flight.arrival_date, 
                    Flight.price, 
                    Destination.city, 
                    Destination.country
                FROM Flight
                JOIN Destination ON Flight.Destination_id = Destination.id
                WHERE Destination.city LIKE ?
            ");
            $stmt->execute(["%$city%"]);
            $results = $stmt->fetchAll();
        } catch (PDOException $e) {
            $error = "Database connection failed.";
        }
    } else {
        $error = "Please enter a city.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Find a Flight – EasyTravel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Georgia', serif;
      background-color: #f8f5f2;
      color: #3b2f2f;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #fff8f4;
      padding: 1.5rem 2rem;
      border-bottom: 1px solid #e8dfd9;
      display: flex;
      justify-content: space-between;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: #8b3e2f;
    }

    .container {
      max-width: 700px;
      margin: 3rem auto;
      text-align: center;
    }

    h1 {
      font-size: 2.5rem;
      color: #7c4229;
      margin-bottom: 1rem;
    }

    input[type="text"] {
      padding: 0.8rem;
      width: 60%;
      border: 1px solid #d8cfc7;
      border-radius: 10px;
      font-size: 1rem;
    }

    button {
      padding: 0.8rem 1.4rem;
      margin-left: 1rem;
      background-color: #7c4229;
      color: #fff8f4;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .results {
      margin-top: 2rem;
      background: #fff8f4;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      text-align: left;
    }

    .flight {
      padding: 1rem 0;
      border-bottom: 1px solid #e6dcd4;
    }

    .flight:last-child {
      border-bottom: none;
    }

    .flight strong {
      color: #8b3e2f;
    }

    footer {
      text-align: center;
      padding: 2rem;
      font-size: 0.9rem;
      color: #7a5a4e;
    }

    .error {
      color: #b03a2e;
      margin-top: 1rem;
    }

    .nav-links a {
      margin-left: 1rem;
      text-decoration: none;
      color: #5a3b2e;
      font-weight: 500;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">EasyTravel</div>
  <div class="nav-links">
      <a href="index.html">Home</a>
      <a href="#">Destinations</a>
      <a href="#">Contact</a>
</div>
</header>

<div class="container">
  <h1>Find Your Flight</h1>
  <form method="GET" action="">
    <input type="text" name="city" placeholder="Enter a city (e.g. Paris, Toronto, Sydney...)" required />
    <button type="submit">Search</button>
  </form>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($results)): ?>
    <div class="results">
      <?php foreach ($results as $flight): ?>
        <div class="flight">
          <strong><?= htmlspecialchars($flight['airline']) ?></strong><br>
          Destination: <?= htmlspecialchars($flight['city']) ?>, <?= htmlspecialchars($flight['country']) ?><br>
          Departure: <?= htmlspecialchars($flight['departure_date']) ?><br>
          Arrival: <?= htmlspecialchars($flight['arrival_date']) ?><br>
          Price: <?= htmlspecialchars($flight['price']) ?> €
        </div>
      <?php endforeach; ?>
    </div>
  <?php elseif (isset($_GET['city']) && empty($results)): ?>
    <p style="margin-top: 2rem;">No flights found for "<strong><?= htmlspecialchars($_GET['city']) ?></strong>".</p>
  <?php endif; ?>
</div>

<footer>
  © 2025 EasyTravel — All rights reserved.
</footer>

</body>
</html>
