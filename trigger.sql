DELIMITER $$

CREATE TRIGGER after_booking_creat_Payment
AFTER INSERT ON Booking
FOR EACH ROW
BEGIN
    DECLARE total_amount INT;

 
    SELECT price INTO total_amount
    FROM Flight
    WHERE id = NEW.Flight_id;


    SELECT price_per_night INTO @hotel_price
    FROM Hotel
    WHERE id = NEW.Hotel_id;

  
    SET total_amount = total_amount + (@hotel_price * 3); 

    INSERT INTO Payment (amount, payment_date, Booking_id)
    VALUES (total_amount, CURDATE(), NEW.id);
END $$

DELIMITER ;







DELIMITER $$

CREATE TRIGGER create_booking_after_flight_and_hotel
AFTER INSERT ON Flight
FOR EACH ROW
BEGIN
    
    DECLARE hotel_id INT;
    
    SELECT id INTO hotel_id
    FROM Hotel
    WHERE Destination_id = NEW.Destination_id
    LIMIT 1;  
    
   
    IF hotel_id IS NOT NULL THEN
        
        INSERT INTO Booking (booking_date, User_id, Flight_id, Hotel_id)
        VALUES (CURDATE(), 1, NEW.id, hotel_id);  
    END IF;
END$$

DELIMITER ;
