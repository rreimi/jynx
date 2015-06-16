-- This view is a helper for faceted search of advertisers, a helper to count the search results per filter

-- 1. PUBLISHERS STATES DATA
-- 2. PUBLISHERS CATEGORIES DATA
USE mercatino;
CREATE OR REPLACE ALGORITHM = UNDEFINED VIEW publishers_faceted_search_view AS
  SELECT
    p.id       AS publisher_id,
    p.state_id AS value,
    'state' COLLATE utf8_unicode_ci AS type,
    s.name     AS label
  FROM `publishers` p JOIN states s ON s.id = p.state_id
  UNION ALL
  SELECT
    pc.publisher_id AS publisher_id,
    pc.category_id  AS value,
    'category' COLLATE utf8_unicode_ci AS type,
    c.name          AS label
  FROM `publishers_categories` pc JOIN categories c ON c.id = pc.category_id
  UNION ALL
  SELECT
    p.id       AS publisher_id,
    p.country_id AS value,
    'country' COLLATE utf8_unicode_ci AS type,
    c.country_name     AS label
  FROM `publishers` p JOIN countries c ON c.id = p.country_id