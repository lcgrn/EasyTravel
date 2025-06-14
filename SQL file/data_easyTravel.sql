-- Sélection de la base
USE travel_booking;

-- Désactivation temporaire des contraintes pour vidage
SET FOREIGN_KEY_CHECKS = 0;

-- Suppression des données
TRUNCATE TABLE Payment;
TRUNCATE TABLE Booking;
TRUNCATE TABLE Hotel;
TRUNCATE TABLE Flight;
TRUNCATE TABLE Destination;
TRUNCATE TABLE User;

-- Réactivation des contraintes
SET FOREIGN_KEY_CHECKS = 1;

-- Insertion dans User
INSERT INTO User (name, email, password) VALUES
('Alice Smith', 'alice@example.com', 'alice123'),
('Bob Johnson', 'bob@example.com', 'bobpass'),
('Charlie Brown', 'charlie@example.com', 'charliepw'),
('Diana Prince', 'diana@example.com', 'wonderwoman'),
('Ethan Hunt', 'ethan@example.com', 'mission123'),
('Fiona Glenanne', 'fiona@example.com', 'burnnotice'),
('George Miller', 'george@example.com', 'george123'),
('Hannah Lee', 'hannah@example.com', 'hannahpwd'),
('Ian Wright', 'ian@example.com', 'wrightpass'),
('Julia Stiles', 'julia@example.com', 'bournepwd'),
('Kevin Hart', 'kevin@example.com', 'laugh123'),
('Laura Palmer', 'laura@example.com', 'twinpeaks'),
('Mike Ross', 'mike@example.com', 'suitespwd'),
('Nina Sharp', 'nina@example.com', 'fringe123'),
('Oscar Wilde', 'oscar@example.com', 'dorian'),
('Penelope Cruz', 'penelope@example.com', 'cruzpass'),
('Quentin Blake', 'quentin@example.com', 'draw123'),
('Rachel Green', 'rachel@example.com', 'fashion'),
('Steve Rogers', 'steve@example.com', 'capshield'),
('Tina Fey', 'tina@example.com', 'comedyqueen');

-- Insertion dans Destination
INSERT INTO Destination (city, country) VALUES
('Paris', 'France'),
('New York', 'USA'),
('Tokyo', 'Japan'),
('Rome', 'Italy'),
('Sydney', 'Australia'),
('London', 'UK'),
('Berlin', 'Germany'),
('Barcelona', 'Spain'),
('Toronto', 'Canada'),
('Dubai', 'UAE');

-- Insertion dans Flight
INSERT INTO Flight (airline, departure_date, arrival_date, price, Destination_id) VALUES
('Air France', '2025-07-01', '2025-07-02', 550, 1),
('Delta Airlines', '2025-08-10', '2025-08-11', 480, 2),
('Japan Airlines', '2025-09-15', '2025-09-16', 720, 3),
('Alitalia', '2025-06-20', '2025-06-21', 460, 4),
('Qantas', '2025-12-05', '2025-12-06', 980, 5),
('British Airways', '2025-07-12', '2025-07-13', 600, 6),
('Lufthansa', '2025-08-22', '2025-08-23', 530, 7),
('Vueling', '2025-09-02', '2025-09-03', 400, 8),
('Air Canada', '2025-10-14', '2025-10-15', 620, 9),
('Emirates', '2025-11-01', '2025-11-02', 850, 10),
('Air France', '2025-07-18', '2025-07-19', 570, 1),
('Delta Airlines', '2025-08-15', '2025-08-16', 490, 2),
('Japan Airlines', '2025-09-18', '2025-09-19', 710, 3),
('Alitalia', '2025-06-25', '2025-06-26', 470, 4),
('Qantas', '2025-12-10', '2025-12-11', 970, 5),
('British Airways', '2025-07-14', '2025-07-15', 610, 6),
('Lufthansa', '2025-08-24', '2025-08-25', 540, 7),
('Vueling', '2025-09-05', '2025-09-06', 390, 8),
('Air Canada', '2025-10-16', '2025-10-17', 630, 9),
('Emirates', '2025-11-04', '2025-11-05', 860, 10);

-- Insertion dans Hotel
INSERT INTO Hotel (name, stars, price_per_night, Destination_id) VALUES
('Le Meurice', 5, 350, 1),
('The Plaza', 5, 450, 2),
('Park Hyatt Tokyo', 5, 500, 3),
('Hotel Artemide', 4, 220, 4),
('Shangri-La Sydney', 5, 400, 5),
('The Savoy', 5, 430, 6),
('Hotel Adlon Kempinski', 5, 410, 7),
('W Barcelona', 5, 390, 8),
('Fairmont Royal York', 4, 300, 9),
('Burj Al Arab', 7, 1000, 10),
('Ibis Paris', 3, 120, 1),
('Yotel NYC', 3, 180, 2),
('Toyoko Inn', 3, 150, 3),
('Hotel Rome Garden', 3, 130, 4),
('Sydney Lodge', 3, 140, 5);

-- Insertion dans Booking
INSERT INTO Booking (booking_date, User_id, Flight_id, Hotel_id) VALUES
('2025-06-01', 1, 1, 1),
('2025-07-15', 2, 2, 2),
('2025-08-20', 3, 3, 3),
('2025-06-10', 4, 4, 4),
('2025-11-15', 5, 5, 5),
('2025-06-05', 6, 6, 6),
('2025-06-06', 7, 7, 7),
('2025-06-07', 8, 8, 8),
('2025-06-08', 9, 9, 9),
('2025-06-09', 10, 10, 10),
('2025-06-10', 11, 11, 11),
('2025-06-11', 12, 12, 12),
('2025-06-12', 13, 13, 13),
('2025-06-13', 14, 14, 14),
('2025-06-14', 15, 15, 15),
('2025-06-15', 16, 16, 1),
('2025-06-16', 17, 17, 2),
('2025-06-17', 18, 18, 3),
('2025-06-18', 19, 19, 4),
('2025-06-19', 20, 20, 5),
('2025-06-20', 1, 1, 6),
('2025-06-21', 2, 2, 7),
('2025-06-22', 3, 3, 8),
('2025-06-23', 4, 4, 9),
('2025-06-24', 5, 5, 10),
('2025-06-25', 6, 6, 11),
('2025-06-26', 7, 7, 12),
('2025-06-27', 8, 8, 13),
('2025-06-28', 9, 9, 14),
('2025-06-29', 10, 10, 15);

-- Insertion dans Payment
INSERT INTO Payment (amount, payment_date, Booking_id) VALUES
(1200, '2025-06-02', 1),
(1450, '2025-07-16', 2),
(1650, '2025-08-21', 3),
(900, '2025-06-11', 4),
(1850, '2025-11-16', 5),
(1300, '2025-06-06', 6),
(1380, '2025-06-07', 7),
(1490, '2025-06-08', 8),
(1530, '2025-06-09', 9),
(1700, '2025-06-10', 10),
(1100, '2025-06-11', 11),
(1200, '2025-06-12', 12),
(1300, '2025-06-13', 13),
(1400, '2025-06-14', 14),
(1600, '2025-06-15', 15),
(1250, '2025-06-16', 16),
(1350, '2025-06-17', 17),
(1450, '2025-06-18', 18),
(1550, '2025-06-19', 19),
(1650, '2025-06-20', 20),
(1700, '2025-06-21', 21),
(1750, '2025-06-22', 22),
(1800, '2025-06-23', 23),
(1900, '2025-06-24', 24),
(2000, '2025-06-25', 25),
(1100, '2025-06-26', 26),
(1200, '2025-06-27', 27),
(1300, '2025-06-28', 28),
(1400, '2025-06-29', 29),
(1500, '2025-06-30', 30);
