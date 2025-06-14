CREATE OR REPLACE VIEW top_hotels AS
SELECT 
    Hotel.id,
    Hotel.name,
    Hotel.stars,
    Hotel.price_per_night,
    Destination.city,
    Destination.country
FROM 
    Hotel
JOIN 
    Destination ON Hotel.Destination_id = Destination.id
WHERE 
    Hotel.stars >= 2
ORDER BY 
    Hotel.stars DESC;
