<?php
$host = "localhost";
$user = "root";
$password = ""; // ou ton mot de passe MySQL
$dbname = "travel_booking4";

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $dbname, 3308);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$city = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : '';

$sql = "
    SELECT Flight.id, Flight.airline, Flight.departure_date, Flight.arrival_date, Flight.price
    FROM Flight
    JOIN Destination ON Flight.Destination_id = Destination.id
    WHERE Destination.city LIKE '%$city%'
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Flight Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    h2 {
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
  </style>
</head>
<body>
  <h2>Flights to "<?php echo htmlspecialchars($city); ?>"</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Airline</th>
          <th>Departure</th>
          <th>Arrival</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['airline']) ?></td>
          <td><?= htmlspecialchars($row['departure_date']) ?></td>
          <td><?= htmlspecialchars($row['arrival_date']) ?></td>
          <td><?= htmlspecialchars($row['price']) ?> €</td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No flights found for this destination.</p>
  <?php endif; ?>

  <?php $conn->close(); ?>
</body>
</html>

