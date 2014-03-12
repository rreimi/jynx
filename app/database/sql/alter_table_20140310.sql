ALTER TABLE  `publishers` ADD  `suggest_products` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `phone2`;

ALTER TABLE  `publishers` ADD  `suggested_products` VARCHAR( 255 ) NOT NULL AFTER  `suggest_products`;

ALTER TABLE  `publishers` ADD  `suggest_services` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `suggested_products`;

ALTER TABLE  `publishers` ADD  `suggested_services` VARCHAR( 255 ) NOT NULL AFTER  `suggest_services`;