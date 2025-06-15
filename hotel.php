<?php
// Database connection
$host = 'localhost';
$dbname = 'travel_booking';
$username = 'root';
$password = 'Shapy_tot1';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Query to get hotels from VIEW
$sql = "SELECT name, stars, price_per_night, city, country FROM top_hotels ORDER BY stars DESC, price_per_night ASC";
$stmt = $pdo->query($sql);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group hotels by star rating
$hotelsByStars = [];
foreach ($hotels as $hotel) {
    $hotelsByStars[$hotel['stars']][] = $hotel;
}
krsort($hotelsByStars); // Sort by stars descending
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Hotels - EasyTravel</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
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
            padding: 80px 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.02"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.02"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.03"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
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

        .stats-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.8;
        }

        /* FILTERS */
        .filters-section {
            background: var(--white);
            padding: 30px 40px;
            border-bottom: 1px solid var(--border-light);
        }

        .filters-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .view-toggle {
            display: flex;
            background: var(--accent-color);
            border-radius: 25px;
            padding: 4px;
        }

        .view-btn {
            padding: 8px 16px;
            border: none;
            background: transparent;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .view-btn.active {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* MAIN CONTENT */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
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
            font-weight: 600;
        }

        .section-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            opacity: 0.9;
        }

        /* STAR CATEGORIES */
        .star-category {
            margin-bottom: 60px;
        }

        .star-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 40px;
            padding: 20px;
            background: var(--accent-color);
            border-radius: 20px;
            border: 2px solid var(--border-light);
        }

        .star-icon {
            font-size: 2rem;
        }

        .star-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .star-info p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .hotels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 32px;
            margin-top: 30px;
        }

        /* HOTEL CARDS */
        .hotel-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            border: 1px solid var(--border-light);
        }

        .hotel-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .hotel-image {
            height: 220px;
            background: var(--gradient);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            overflow: hidden;
        }

        .hotel-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.05) 50%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }

        .hotel-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .hotel-content {
            padding: 24px;
        }

        .hotel-header {
            margin-bottom: 16px;
        }

        .hotel-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 600;
            line-height: 1.3;
        }

        .hotel-location {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .hotel-stars {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 16px;
        }

        .stars {
            color: var(--gold);
            font-size: 16px;
            letter-spacing: 2px;
        }

        .star-text {
            font-size: 12px;
            color: var(--text-secondary);
            margin-left: 6px;
        }

        .hotel-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--border-light);
        }

        .hotel-price {
            display: flex;
            flex-direction: column;
        }

        .price-amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .price-period {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .book-btn {
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 62, 47, 0.3);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* ANIMATIONS */
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hotel-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .hotel-card:nth-child(1) { animation-delay: 0.1s; }
        .hotel-card:nth-child(2) { animation-delay: 0.2s; }
        .hotel-card:nth-child(3) { animation-delay: 0.3s; }
        .hotel-card:nth-child(4) { animation-delay: 0.4s; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .hero-section {
                padding: 60px 20px;
            }

            .main-content {
                padding: 40px 20px;
            }

            .hotels-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .stats-container {
                gap: 20px;
            }

            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }

            .star-header {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .hotel-content {
                padding: 20px;
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
                <span>Premium Hotels</span>
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
            <div class="hero-badge">🏨 Curated Collection</div>
            <h1 class="hero-title">Premium Hotels</h1>
            <p class="hero-subtitle">Experience exceptional comfort and luxury at our carefully selected hotels worldwide</p>
            
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-number"><?= count($hotels) ?></span>
                    <span class="stat-label">Premium Hotels</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= count(array_unique(array_column($hotels, 'country'))) ?></span>
                    <span class="stat-label">Countries</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Concierge Service</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="filters-section">
        <div class="filters-container">
            <div class="filter-group">
                <span class="filter-label">Sorted by:</span>
                <span>Luxury Rating & Best Prices</span>
            </div>
            
            <div class="view-toggle">
                <button class="view-btn active">Grid View</button>
                <button class="view-btn">List View</button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <?php if (empty($hotels)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏨</div>
                <h3>No hotels found</h3>
                <p>Please check back later for our premium hotel collection.</p>
            </div>
        <?php else: ?>
            <?php foreach ($hotelsByStars as $starRating => $starHotels): ?>
                <div class="star-category">
                    <div class="star-header">
                        <div class="star-icon">
                            <?php if ($starRating == 5): ?>
                                👑
                            <?php elseif ($starRating == 4): ?>
                                ⭐
                            <?php elseif ($starRating == 3): ?>
                                🌟
                            <?php else: ?>
                                ✨
                            <?php endif; ?>
                        </div>
                        <div class="star-info">
                            <h3><?= $starRating ?>-Star <?= $starRating == 5 ? 'Luxury' : ($starRating == 4 ? 'Premium' : 'Select') ?> Collection</h3>
                            <p><?= count($starHotels) ?> exceptional <?= count($starHotels) == 1 ? 'property' : 'properties' ?> available</p>
                        </div>
                    </div>

                    <div class="hotels-grid">
                        <?php foreach ($starHotels as $hotel): ?>
                            <div class="hotel-card" onclick="bookHotel('<?= htmlspecialchars($hotel['name']) ?>')">
                                <div class="hotel-image">
                                    🏨
                                    <div class="hotel-badge">
                                        <?= $hotel['stars'] == 5 ? 'Luxury' : ($hotel['stars'] == 4 ? 'Premium' : 'Quality') ?>
                                    </div>
                                </div>
                                
                                <div class="hotel-content">
                                    <div class="hotel-header">
                                        <h4 class="hotel-name"><?= htmlspecialchars($hotel['name']) ?></h4>
                                        
                                        <div class="hotel-location">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                <path d="M21 10C21 17L12 23L3 10C3 5.029 7.029 1 12 1S21 5.029 21 10Z" stroke="currentColor" stroke-width="2"/>
                                                <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                            <?= htmlspecialchars($hotel['city']) ?>, <?= htmlspecialchars($hotel['country']) ?>
                                        </div>
                                        
                                        <div class="hotel-stars">
                                            <span class="stars"><?= str_repeat('★', $hotel['stars']) ?></span>
                                            <span class="star-text"><?= $hotel['stars'] ?> Star Hotel</span>
                                        </div>
                                    </div>
                                    
                                    <div class="hotel-footer">
                                        <div class="hotel-price">
                                            <span class="price-amount">€<?= number_format($hotel['price_per_night'], 0) ?></span>
                                            <span class="price-period">per night</span>
                                        </div>
                                        
                                        <button class="book-btn" onclick="event.stopPropagation(); bookHotel('<?= htmlspecialchars($hotel['name']) ?>')">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <script>
        // Hotel booking function
        function bookHotel(hotelName) {
            alert(`Booking ${hotelName}... Redirecting to booking page.`);
            // Here you can redirect to booking page
            // window.location.href = 'book_now.php?hotel=' + encodeURIComponent(hotelName);
        }

        // View toggle functionality
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const hotelsGrid = document.querySelector('.hotels-grid');
                if (this.textContent === 'List View') {
                    hotelsGrid.style.gridTemplateColumns = '1fr';
                } else {
                    hotelsGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(350px, 1fr))';
                }
            });
        });

        // Smooth scroll for star categories
        document.querySelectorAll('.star-header').forEach(header => {
            header.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Add loading state for book buttons
        document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Booking...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 2000);
            });
        });
    </script>
</body>
</html>
