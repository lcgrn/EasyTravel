<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'travel_booking';
$user = 'root';
$password = 'Shapy_tot1';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des vols et hôtels
$flights = $pdo->query("SELECT Flight.id, airline, departure_date, arrival_date, price, city, country 
                        FROM Flight 
                        JOIN Destination ON Flight.Destination_id = Destination.id")->fetchAll();

$hotels = $pdo->query("SELECT Hotel.id, name, stars, price_per_night, city, country 
                       FROM Hotel 
                       JOIN Destination ON Hotel.Destination_id = Destination.id")->fetchAll();

$message = "";

// Réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = 1;
    $flightId = $_POST['flight_id'] ?? null;
    $hotelId = $_POST['hotel_id'] ?? null;

    if ($flightId && $hotelId) {
        $stmt = $pdo->prepare("INSERT INTO Booking (booking_date, User_id, Flight_id, Hotel_id) 
                               VALUES (CURDATE(), ?, ?, ?)");
        if ($stmt->execute([$userId, $flightId, $hotelId])) {
            $bookingId = $pdo->lastInsertId(); // Récupère l’ID de la réservation insérée
            header("Location: confirmation.php?booking_id=" . $bookingId); // Redirection avec paramètre
            exit;
        } else {
            $message = "❌ An error occurred. Please try again.";
        }
    } else {
        $message = "⚠️ Please select both a flight and a hotel.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Now - EasyTravel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f6f2;
      color: #333;
    }

    header {
      padding: 30px;
      text-align: center;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.8rem;
      margin: 0;
      color: #2c2c2c;
    }

    main {
      max-width: 750px;
      margin: 40px auto;
      background-color: #fff;
      padding: 40px;
      border-radius: 18px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    label {
      font-weight: 600;
      display: block;
      margin-top: 25px;
      margin-bottom: 10px;
    }

    select, button {
      width: 100%;
      padding: 14px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 10px;
    }

    button {
      margin-top: 30px;
      background-color: #e85a4f;
      color: white;
      font-weight: 600;
      border: none;
      transition: background 0.3s ease;
      cursor: pointer;
    }

    button:hover {
      background-color: #d0443a;
    }

    .message {
      margin-top: 20px;
      padding: 15px 20px;
      border-radius: 10px;
      font-weight: 500;
      background-color: #fff3cd;
      color: #856404;
      border-left: 5px solid #ffeeba;
    }

    footer {
      text-align: center;
      padding: 40px 0;
      font-size: 0.9rem;
      color: #aaa;
    }

    @media (max-width: 600px) {
      main {
        padding: 25px;
      }

      header h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Build Your Custom Trip</h1>
</header>

<main>
  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label for="flight_id">✈️ Select your flight</label>
    <select name="flight_id" id="flight_id" required>
      <option value="">-- Choose a flight --</option>
      <?php foreach ($flights as $f): ?>
        <option value="<?= $f['id'] ?>">
          <?= $f['airline'] ?> — <?= $f['city'] ?> (<?= $f['country'] ?>)
          | <?= date('M d', strtotime($f['departure_date'])) ?> → <?= date('M d', strtotime($f['arrival_date'])) ?>
          | <?= $f['price'] ?> €
        </option>
      <?php endforeach; ?>
    </select>

    <label for="hotel_id">🏨 Select your hotel</label>
    <select name="hotel_id" id="hotel_id" required>
      <option value="">-- Choose a hotel --</option>
      <?php foreach ($hotels as $h): ?>
        <option value="<?= $h['id'] ?>">
          <?= $h['name'] ?> (<?= $h['stars'] ?>★) — <?= $h['city'] ?> | <?= $h['price_per_night'] ?> €/night
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit">🧳 Confirm Booking</button>
  </form>
</main>

<footer>
  &copy; <?= date("Y") ?> EasyTravel. All rights reserved.
</footer>

</body>
</html>
