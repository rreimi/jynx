UPDATE users SET role="Basic" WHERE is_publisher=1;
UPDATE users SET is_publisher=0 WHERE role="Publisher";
