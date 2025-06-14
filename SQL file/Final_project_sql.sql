-- Création de la base
CREATE DATABASE IF NOT EXISTS travel_booking;
USE travel_booking;

-- Table User
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20),
    email VARCHAR(150),
    password VARCHAR(50)
);

-- Table Destination
CREATE TABLE Destination (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(50),
    country VARCHAR(50)
);

-- Table Flight
CREATE TABLE Flight (
    id INT AUTO_INCREMENT PRIMARY KEY,
    airline VARCHAR(50),
    departure_date DATE,
    arrival_date DATE,
    price INT,
    Destination_id INT,
    FOREIGN KEY (Destination_id) REFERENCES Destination(id)
);

-- Table Hotel
CREATE TABLE Hotel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    stars INT,
    price_per_night INT,
    Destination_id INT,
    FOREIGN KEY (Destination_id) REFERENCES Destination(id)
);

-- Table Booking
CREATE TABLE Booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_date DATE,
    User_id INT,
    Flight_id INT,
    Hotel_id INT,
    FOREIGN KEY (User_id) REFERENCES User(id),
    FOREIGN KEY (Flight_id) REFERENCES Flight(id),
    FOREIGN KEY (Hotel_id) REFERENCES Hotel(id)
);

-- Table Payment
CREATE TABLE Payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount INT,
    payment_date DATE,
    Booking_id INT,
    FOREIGN KEY (Booking_id) REFERENCES Booking(id)
);

