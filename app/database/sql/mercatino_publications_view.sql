USE mercatino;
CREATE OR REPLACE ALGORITHM = UNDEFINED VIEW publications_view AS
  SELECT p. * ,
    u.seller_name,
    u.state_id,
    u.city,
    i.image_url,
    GROUP_CONCAT( DISTINCT (c.id )) AS categories_id,
    GROUP_CONCAT( DISTINCT (c.name )) AS categories,
    GROUP_CONCAT( DISTINCT (t.full_name )) AS contacts,
    GROUP_CONCAT( DISTINCT (s.id )) AS contacts_states_id,
    GROUP_CONCAT( DISTINCT (t.city )) AS contacts_city,
    GROUP_CONCAT( DISTINCT (s.name)) AS contacts_state,
    COUNT(DISTINCT (r.id)) AS reports,
    COUNT(DISTINCT (a.id)) AS ratings
  FROM publications AS p
    LEFT JOIN publications_categories AS cp ON p.id = cp.publication_id
    LEFT JOIN categories AS c ON c.id = cp.category_id
    LEFT JOIN publishers AS u ON u.id = p.publisher_id
    LEFT JOIN publications_contacts AS pc ON p.id = pc.publication_id
    LEFT JOIN contacts AS t ON t.id = pc.contact_id
    LEFT JOIN states AS s ON s.id = t.state_id
    LEFT JOIN publications_reports AS r ON r.publication_id = p.id
    LEFT JOIN publications_images AS i ON p.publication_image_id = i.id
    LEFT JOIN publications_ratings AS a ON a.publication_id = p.id
  GROUP BY p.id
