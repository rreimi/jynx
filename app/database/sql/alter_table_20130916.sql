ALTER TABLE  `publishers` DROP  `image` ;

ALTER TABLE  `publishers` ADD  `avatar` VARCHAR( 255 ) NULL AFTER  `phone2`;