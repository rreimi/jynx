ALTER TABLE `jobs` ADD `status` ENUM('Draft','Published','On_Hold','Suspended','Finished','Trashed') NOT NULL AFTER  `contact_email`;
ALTER TABLE `publications` ADD `latitude` DECIMAL(8,6) NULL AFTER `rating_avg` ,
ADD `longitude` DECIMAL(8,6) NULL AFTER `latitude`;