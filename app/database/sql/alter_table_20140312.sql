ALTER TABLE  `publishers` ADD  `status_publisher` ENUM(  'Pending',  'Approved',  'Denied',  'Suspended' ) NOT NULL DEFAULT  'Approved' AFTER  `rif_ci`;