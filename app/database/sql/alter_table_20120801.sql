ALTER TABLE  `users` ADD  `step` TINYINT( 1 ) NOT NULL DEFAULT  '-1' AFTER  `is_publisher`;

UPDATE `users` SET step='0';
UPDATE `users` SET step='2' WHERE role='Basic';

ALTER TABLE `users` DROP `status`;
ALTER TABLE `users` ADD `status` ENUM('Active','Suspended','Inactive') NOT NULL AFTER `password`;