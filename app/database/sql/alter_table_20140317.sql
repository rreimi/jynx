ALTER TABLE  `contacts` ADD  `is_main` VARCHAR( 0 ) NULL AFTER  `address` ;
ALTER TABLE `publications` DROP `show_pub_as_contact`;