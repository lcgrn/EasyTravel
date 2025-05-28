CREATE OR REPLACE VIEW top_hotels AS
SELECT id, name, stars, price_per_night
FROM Hotel
WHERE stars >= 2
ORDER BY stars DESC;
