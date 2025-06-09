CREATE TABLE Booking_Log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    booking_id INT,
    action VARCHAR(100),
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
