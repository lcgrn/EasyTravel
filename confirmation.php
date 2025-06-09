<?php
$host = 'localhost';
$dbname = 'travel_booking';
$user = 'root';
$password = 'Shapy_tot1';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get booking ID from URL
$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($bookingId === 0) {
    die("Invalid booking.");
}

// Get booking details
$sql = "SELECT 
            h.name AS hotel_name,
            h.price_per_night,
            f.airline,
            f.departure_date,
            f.arrival_date,
            f.price AS flight_price,
            b.booking_date
        FROM Booking b
        JOIN Flight f ON b.Flight_id = f.id
        JOIN Hotel h ON b.Hotel_id = h.id
        WHERE b.id = :booking_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['booking_id' => $bookingId]);
$data = $stmt->fetch();

if (!$data) {
    die("Booking not found.");
}

$total_price = $data['price_per_night'] + $data['flight_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmed - EasyTravel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to bottom right, #fdfaf6, #ece6df);
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: #333;
    }

    .card {
      background-color: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 600px;
      text-align: center;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      margin-bottom: 20px;
    }

    .details {
      text-align: left;
      margin-top: 30px;
      font-size: 1rem;
      line-height: 1.6;
    }

    .icon {
      font-size: 3rem;
      margin-bottom: 20px;
    }

    .btn-home {
      margin-top: 30px;
      padding: 12px 25px;
      background-color: #e85a4f;
      color: white;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn-home:hover {
      background-color: #d0443a;
    }

    .highlight {
      font-weight: bold;
      color: #222;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">🎉</div>
    <h1>Your booking is confirmed!</h1>
    <p>Thank you for choosing EasyTravel. Here are the details of your trip:</p>

    <div class="details">
      <p><span class="highlight">Booking Date:</span> <?= htmlspecialchars($data['booking_date']) ?></p>
      <p><span class="highlight">Hotel:</span> <?= htmlspecialchars($data['hotel_name']) ?> (<?= number_format($data['price_per_night'], 2) ?> € / night)</p>
      <p><span class="highlight">Flight:</span> <?= htmlspecialchars($data['airline']) ?> — <?= htmlspecialchars($data['departure_date']) ?> ➡️ <?= htmlspecialchars($data['arrival_date']) ?> (<?= number_format($data['flight_price'], 2) ?> €)</p>
      <p><span class="highlight">Total Price:</span> <strong><?= number_format($total_price, 2) ?> €</strong></p>
    </div>

    <a href="index.html" class="btn-home">Return to homep
