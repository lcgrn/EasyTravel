DELIMITER $$

CREATE TRIGGER create_booking_after_flight_and_hotel
AFTER INSERT ON Flight
FOR EACH ROW
BEGIN
  DECLARE hotel_id INT;
  DECLARE nights INT DEFAULT 3; -- valeur par défaut si non fournie ailleurs

  -- Sélection d’un hôtel pour la même destination
  SELECT id INTO hotel_id
  FROM Hotel
  WHERE Destination_id = NEW.Destination_id
  LIMIT 1;

  IF hotel_id IS NOT NULL THEN
    INSERT INTO Booking (booking_date, User_id, Flight_id, Hotel_id, nights)
    VALUES (CURDATE(), 1, NEW.id, hotel_id, nights);
  END IF;
END$$

DELIMITER ;
