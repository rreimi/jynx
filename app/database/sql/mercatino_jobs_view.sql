CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW jobs_view AS
  SELECT
    jobs.*,
    states.name AS state ,
    IF(TRIM(COALESCE(jobs.city,'')) = '',states.name,CONCAT_WS(' - ',states.name, jobs.city)) AS location,
    GROUP_CONCAT(DISTINCT areas.id SEPARATOR ', ') AS area_ids,
    GROUP_CONCAT(DISTINCT areas.name SEPARATOR ', ') AS areas,
    GROUP_CONCAT(DISTINCT careers.id SEPARATOR ', ') AS career_ids,
    GROUP_CONCAT(DISTINCT careers.name SEPARATOR ', ') AS careers
  FROM jobs
    JOIN jobs_areas ON jobs.id=jobs_areas.job_id
    JOIN areas ON jobs_areas.area_id=areas.id
    LEFT JOIN jobs_careers ON jobs.id=jobs_careers.job_id
    LEFT JOIN careers ON jobs_careers.career_id=careers.id
    JOIN states ON jobs.state_id=states.id
  GROUP BY jobs.id