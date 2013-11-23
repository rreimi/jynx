ALTER TABLE  `contacts` ADD  `other_phone` VARCHAR( 20 ) NULL AFTER  `phone`;
ALTER TABLE  `contacts` CHANGE  `full_name`  `full_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE  `contacts` CHANGE  `distributor`  `distributor` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE  `contacts` CHANGE  `city`  `city` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE  `contacts` CHANGE  `address`  `address` VARCHAR( 300 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE  `contacts` CHANGE  `email`  `email` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `contacts` CHANGE  `phone`  `phone` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;