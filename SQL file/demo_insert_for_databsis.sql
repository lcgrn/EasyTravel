
-- Destination
INSERT INTO Destination (city, country) VALUES
('Paris', 'France'),
('London', 'United Kingdom'),
('New York', 'USA'),
('Tokyo', 'Japan'),
('Sydney', 'Australia'),
('Berlin', 'Germany'),
('Rome', 'Italy'),
('Barcelona', 'Spain'),
('Dubai', 'UAE'),
('Los Angeles', 'USA'),
('Singapore', 'Singapore'),
('Amsterdam', 'Netherlands'),
('Venice', 'Italy'),
('Vienna', 'Austria'),
('Copenhagen', 'Denmark'),
('Cape Town', 'South Africa'),
('Bangkok', 'Thailand'),
('Rio de Janeiro', 'Brazil'),
('Istanbul', 'Turkey'),
('Moscow', 'Russia');


-- User
INSERT INTO User (name, email, password) VALUES
('Ben', 'ben@example.com', 'password'),
('Sam', 'sam@example.com', 'password'),
('Max', 'max@example.com', 'password'),
('Leo', 'leo@example.com', 'password'),
('Lia', 'lia@example.com', 'password'),
('Zoe', 'zoe@example.com', 'password'),
('Eli', 'eli@example.com', 'password'),
('Tom', 'tom@example.com', 'password'),
('Ray', 'ray@example.com', 'password'),
('Jax', 'jax@example.com', 'password'),
('Dan', 'dan@example.com', 'password'),
('Liv', 'liv@example.com', 'password'),
('Aya', 'aya@example.com', 'password'),
('Kai', 'kai@example.com', 'password'),
('Nia', 'nia@example.com', 'password'),
('Jay', 'jay@example.com', 'password'),
('Sid', 'sid@example.com', 'password'),
('Kay', 'kay@example.com', 'password'),
('Roy', 'roy@example.com', 'password'),
('Rex', 'rex@example.com', 'password');


-- Fliths
INSERT INTO Flight (airline, departure_date, arrival_date, price, Destination_id) VALUES
('Air France', '2025-06-01', '2025-06-02', 500, 1),
('British Airways', '2025-06-05', '2025-06-06', 600, 2),
('American Airlines', '2025-06-10', '2025-06-11', 450, 3),
('Japan Airlines', '2025-06-15', '2025-06-16', 550, 4),
('Qantas', '2025-06-20', '2025-06-21', 650, 5),
('Lufthansa', '2025-06-25', '2025-06-26', 700, 6),
('Alitalia', '2025-06-30', '2025-07-01', 800, 7),
('Iberia', '2025-07-05', '2025-07-06', 750, 8),
('Emirates', '2025-07-10', '2025-07-11', 900, 9),
('Delta Airlines', '2025-07-15', '2025-07-16', 850, 10),
('Singapore Airlines', '2025-07-20', '2025-07-21', 950, 11),
('KLM', '2025-07-25', '2025-07-26', 1000, 12),
('Venetian Airlines', '2025-07-30', '2025-07-31', 1100, 13),
('Austrian Airlines', '2025-08-01', '2025-08-02', 1200, 14),
('SAS', '2025-08-05', '2025-08-06', 1300, 15),
('South African Airways', '2025-08-10', '2025-08-11', 1400, 16),
('Thai Airways', '2025-08-15', '2025-08-16', 1500, 17),
('LATAM Airlines', '2025-08-20', '2025-08-21', 1600, 18),
('Turkish Airlines', '2025-08-25', '2025-08-26', 1700, 19),
('Aeroflot', '2025-08-30', '2025-08-31', 1800, 20);


-- Hotels
INSERT INTO Hotel (name, stars, price_per_night, Destination_id) VALUES
('Le Meurice', 5, 50, 1),
('The Langham', 5, 45, 2),
('The Plaza', 5, 60, 3),
('The Peninsula Tokyo', 5, 55, 4),
('The Langham Sydney', 5, 65, 5),
('Hotel Adlon Kempinski', 5, 70, 6),
('Hotel de Russie', 5, 75, 7),
('Majestic Hotel & Spa', 5, 60, 8),
('Burj Al Arab Jumeirah', 7, 150, 9),
('The Ritz-Carlton', 5, 95, 10),
('Marina Bay Sands', 5, 100, 11),
('The Dylan Amsterdam', 5, 85, 12),
('Ca Sagredo Hotel', 5, 70, 13),
('Hotel Sacher Wien', 5, 90, 14),
('Nimb Hotel', 5, 100, 15),
('One&Only Cape Town', 5, 120, 16),
('Anantara Riverside Bangkok Resort', 5, 75, 17),
('Belmond Copacabana Palace', 5, 120, 18),
('Four Seasons Hotel Istanbul', 5, 80, 19),
('Ararat Park Hyatt Moscow', 5, 95, 20);
