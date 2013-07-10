CREATE OR REPLACE ALGORITHM = UNDEFINED VIEW publications_view AS
SELECT p. * , u.seller_name, u.state_id, u.city, s.name AS state, GROUP_CONCAT( DISTINCT (
c.name
) ) AS categories_name, GROUP_CONCAT( DISTINCT (
c.id
) ) AS categories_id, GROUP_CONCAT( DISTINCT (
CONCAT( t.first_name, ' ', t.last_name ) )
) AS contacts, t.id AS contact_id
FROM publications AS p
LEFT JOIN publications_categories AS cp ON p.id = cp.publication_id
LEFT JOIN categories AS c ON c.id = cp.category_id
LEFT JOIN publishers AS u ON u.id = p.publisher_id
LEFT JOIN states AS s ON s.id = u.state_id
LEFT JOIN contacts AS t ON t.publisher_id = u.id
GROUP BY p.id