<?php
// Database connection
$host = 'localhost';
$dbname = 'travel_booking';
$user = 'root';
$password = 'Shapy_tot1';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Get number of nights from form, default: 3
$nights = isset($_GET['nights']) ? max(1, (int)$_GET['nights']) : 3;

// REQUÊTE CORRIGÉE : Join avec la table Destination pour récupérer city et country
$stmt = $pdo->query("
    SELECT 
        h.id,
        h.name, 
        h.stars, 
        h.price_per_night,
        d.city,
        d.country
    FROM Hotel h
    LEFT JOIN Destination d ON h.Destination_id = d.id
    ORDER BY h.stars DESC, h.price_per_night ASC
");
$hotels = $stmt->fetchAll();

// Calculate statistics
$totalHotels = count($hotels);
$avgPrice = $totalHotels > 0 ? array_sum(array_column($hotels, 'price_per_night')) / $totalHotels : 0;
$minPrice = $totalHotels > 0 ? min(array_column($hotels, 'price_per_night')) : 0;
$maxPrice = $totalHotels > 0 ? max(array_column($hotels, 'price_per_night')) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Price Calculator - EasyTravel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8b3e2f;
            --secondary-color: #7c4229;
            --accent-color: #f1e7df;
            --text-primary: #3b2f2f;
            --text-secondary: #5a3b2e;
            --background: #f8f5f2;
            --white: #fff8f4;
            --border-light: #e4dcd4;
            --shadow: rgba(139, 62, 47, 0.1);
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            --gold: #d4af37;
            --success: #27ae60;
            --info: #3498db;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            background: rgba(255, 248, 244, 0.95);
            backdrop-filter: blur(15px);
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border-light);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .breadcrumb a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: var(--primary-color);
        }

        .back-btn {
            background: var(--gradient);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--shadow);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(139, 62, 47, 0.2);
        }

        /* HERO SECTION */
        .hero-section {
            background: var(--gradient);
            color: white;
            text-align: center;
            padding: 80px 40px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 18px;
            margin-bottom: 32px;
            opacity: 0.9;
        }

        /* CALCULATOR SECTION */
        .calculator-section {
            background: white;
            margin: -30px auto 0;
            max-width: 1200px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
            overflow: hidden;
        }

        .calculator-header {
            background: var(--accent-color);
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid var(--border-light);
        }

        .calculator-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .calculator-subtitle {
            color: var(--text-secondary);
            font-size: 16px;
        }

        .calculator-form {
            padding: 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 16px;
        }

        .nights-input {
            width: 120px;
            padding: 16px;
            border: 2px solid var(--border-light);
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            color: var(--text-primary);
            transition: all 0.3s ease;
            background: white;
        }

        .nights-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(139, 62, 47, 0.1);
        }

        .update-btn {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(139, 62, 47, 0.2);
        }

        .update-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(139, 62, 47, 0.3);
        }

        .quick-select {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .quick-btn {
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 25px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .quick-btn:hover,
        .quick-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* STATISTICS */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 40px;
            background: var(--accent-color);
            border-bottom: 1px solid var(--border-light);
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* HOTELS SECTION */
        .hotels-section {
            padding: 60px 40px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .results-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: var(--text-secondary);
        }

        .hotels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 32px;
        }

        /* HOTEL CARDS */
        .hotel-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            border: 1px solid var(--border-light);
            position: relative;
        }

        .hotel-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .hotel-header {
            background: var(--gradient);
            color: white;
            padding: 24px;
            position: relative;
        }

        .hotel-star-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .hotel-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .hotel-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            opacity: 0.9;
        }

        .hotel-stars {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
        }

        .stars {
            color: var(--gold);
            font-size: 16px;
            letter-spacing: 2px;
        }

        .hotel-content {
            padding: 32px 24px;
        }

        .price-breakdown {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .price-item {
            text-align: center;
            padding: 20px;
            background: var(--accent-color);
            border-radius: 16px;
        }

        .price-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .price-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .total-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 24px;
            border-radius: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .total-content {
            position: relative;
            z-index: 2;
        }

        .total-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .total-price {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .savings-badge {
            display: inline-block;
            background: var(--success);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .no-destination {
            color: var(--text-secondary);
            font-style: italic;
            opacity: 0.7;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .hero-section {
                padding: 60px 20px 40px;
            }

            .calculator-form {
                flex-direction: column;
                padding: 30px 20px;
            }

            .hotels-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .results-info {
                flex-direction: column;
                gap: 20px;
            }

            .price-breakdown {
                grid-template-columns: 1fr;
            }

            .hotels-section {
                padding: 40px 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .calculator-header {
                padding: 30px 20px;
            }

            .total-price {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="index.html" class="logo">EasyTravel</a>
            
            <div class="breadcrumb">
                <a href="index.html">Home</a>
                <span>→</span>
                <span>Price Calculator</span>
            </div>
            
            <a href="index.html" class="back-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2"/>
                </svg>
                Back to Home
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-badge">💰 Smart Planning Tool</div>
            <h1 class="hero-title">Hotel Price Calculator</h1>
            <p class="hero-subtitle">Calculate your total accommodation costs for any duration and find the perfect hotel within your budget</p>
        </div>
    </section>

    <!-- Calculator Section -->
    <section class="calculator-section">
        <div class="calculator-header">
            <h2 class="calculator-title">Plan Your Stay</h2>
            <p class="calculator-subtitle">Select the number of nights to see updated prices for all our hotels</p>
        </div>

        <div class="calculator-form">
            <form method="GET" style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
                <div class="form-group">
                    <label for="nights" class="form-label">Number of Nights</label>
                    <input type="number" 
                           name="nights" 
                           id="nights"
                           class="nights-input"
                           min="1" 
                           max="30"
                           value="<?= htmlspecialchars($nights) ?>">
                </div>
                
                <button type="submit" class="update-btn">
                    Calculate Prices
                </button>
            </form>
            
            <div class="quick-select">
                <span style="font-weight: 600; color: var(--text-secondary); margin-right: 10px;">Quick Select:</span>
                <button type="button" class="quick-btn" onclick="setNights(1)">1 Night</button>
                <button type="button" class="quick-btn" onclick="setNights(3)">3 Nights</button>
                <button type="button" class="quick-btn" onclick="setNights(7)">1 Week</button>
                <button type="button" class="quick-btn" onclick="setNights(14)">2 Weeks</button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stat-card">
                <span class="stat-value"><?= $totalHotels ?></span>
                <span class="stat-label">Available Hotels</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">€<?= number_format($minPrice, 0) ?></span>
                <span class="stat-label">Starting From</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">€<?= number_format($avgPrice, 0) ?></span>
                <span class="stat-label">Average Price</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">€<?= number_format($maxPrice, 0) ?></span>
                <span class="stat-label">Luxury Options</span>
            </div>
        </div>
    </section>

    <!-- Hotels Section -->
    <section class="hotels-section">
        <div class="section-header">
            <h2 class="section-title">Hotel Pricing for <?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></h2>
            
            <div class="results-info">
                <div class="info-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M21 10C21 17L12 23L3 10C3 5.029 7.029 1 12 1S21 5.029 21 10Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span><?= $totalHotels ?> Hotels Available</span>
                </div>
                <div class="info-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2V22M17 5H9.5C8.11929 5 7 6.11929 7 7.5S8.11929 10 9.5 10H14.5C15.8807 10 17 11.1193 17 12.5S15.8807 15 14.5 15H5" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span>Real-time Pricing</span>
                </div>
                <div class="info-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span>Best Price Guarantee</span>
                </div>
            </div>
        </div>

        <?php if (empty($hotels)): ?>
            <div style="text-align: center; padding: 60px; color: var(--text-secondary);">
                <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;">🏨</div>
                <h3>No hotels found</h3>
                <p>Please check back later for our hotel collection.</p>
            </div>
        <?php else: ?>
            <div class="hotels-grid">
                <?php foreach ($hotels as $index => $hotel): 
                    // Calculate total price using SQL function
                    $stmt = $pdo->prepare("SELECT total_price_per_stay(:price, :nights) AS total_price");
                    $stmt->execute([
                        'price' => $hotel['price_per_night'],
                        'nights' => $nights
                    ]);
                    $result = $stmt->fetch();
                    $total_price = $result['total_price'];
                    
                    // Calculate potential savings vs most expensive option
                    $savings = ($maxPrice - $hotel['price_per_night']) * $nights;
                ?>
                    <div class="hotel-card" style="animation-delay: <?= $index * 0.1 ?>s;">
                        <div class="hotel-header">
                            <div class="hotel-star-badge">
                                <?= $hotel['stars'] ?>★ Hotel
                            </div>
                            
                            <h3 class="hotel-name"><?= htmlspecialchars($hotel['name']) ?></h3>
                            
                            <div class="hotel-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 10C21 17L12 23L3 10C3 5.029 7.029 1 12 1S21 5.029 21 10Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <?php if (!empty($hotel['city']) && !empty($hotel['country'])): ?>
                                    <?= htmlspecialchars($hotel['city']) ?>, <?= htmlspecialchars($hotel['country']) ?>
                                <?php else: ?>
                                    <span class="no-destination">Location not specified</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="hotel-stars">
                                <span class="stars"><?= str_repeat('★', $hotel['stars']) ?></span>
                                <span style="font-size: 12px; opacity: 0.8;"><?= $hotel['stars'] ?> Star Rating</span>
                            </div>
                        </div>

                        <div class="hotel-content">
                            <div class="price-breakdown">
                                <div class="price-item">
                                    <div class="price-label">Per Night</div>
                                    <div class="price-value">€<?= number_format($hotel['price_per_night'], 0) ?></div>
                                </div>
                                <div class="price-item">
                                    <div class="price-label"><?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></div>
                                    <div class="price-value">€<?= number_format($nights, 0) ?>×</div>
                                </div>
                            </div>

                            <div class="total-section">
                                <div class="total-content">
                                    <div class="total-label">Total Cost for <?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></div>
                                    <div class="total-price">€<?= number_format($total_price, 0) ?></div>
                                    
                                    <?php if ($savings > 0): ?>
                                        <div class="savings-badge">
                                            Save €<?= number_format($savings, 0) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <script>
        // Set nights and update form
        function setNights(nights) {
            document.getElementById('nights').value = nights;
            
            // Update active state for quick select buttons
            document.querySelectorAll('.quick-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Auto-submit form after short delay for better UX
            setTimeout(() => {
                document.querySelector('form').submit();
            }, 300);
        }

        // Add loading state to calculate button
        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('.update-btn');
            const originalText = btn.textContent;
            btn.textContent = 'Calculating...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 1000);
        });

        // Highlight current nights selection
        document.addEventListener('DOMContentLoaded', function() {
            const currentNights = <?= $nights ?>;
            document.querySelectorAll('.quick-btn').forEach(btn => {
                if (btn.textContent.includes(currentNights + ' Night')) {
                    btn.classList.add('active');
                }
            });
        });

        // Add smooth scroll animation
        document.querySelectorAll('.hotel-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    </script>
</body>
</html>
