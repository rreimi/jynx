ALTER TABLE  `contacts` ADD  `state_id` INT( 11 ) NOT NULL AFTER  `publisher_id`
ALTER TABLE  `publishers` ADD  `address` VARCHAR( 255 ) NULL AFTER  `city`
ALTER TABLE  `publications` ADD  `show_pub_as_contact` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `longitude`