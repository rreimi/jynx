CREATE OR REPLACE ALGORITHM = UNDEFINED VIEW home_publications_view AS
SELECT p.id, p.title, p.rating_avg, p.created_at, u.seller_name, i.image_url, SUM(IF(v.created_at>= date(now())-7, 1, 0)) AS last_days_visits
FROM publications AS p
LEFT JOIN publishers AS u ON p.publisher_id = u.id
LEFT JOIN publications_images AS i ON p.publication_image_id = i.id
LEFT JOIN publications_visits AS v ON p.id = v.publication_id
WHERE p.status="Published" AND p.from_date<=now() AND p.to_date>=now()
GROUP BY v.publication_id;