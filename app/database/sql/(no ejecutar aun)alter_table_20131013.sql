ALTER TABLE  `log` CHANGE  `previous_value`  `previous_value` VARCHAR( 1000 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
ALTER TABLE  `log` CHANGE  `final_value`  `final_value` VARCHAR( 1000 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
ALTER TABLE  `log` ADD  `to_publisher_id` INT( 11 ) NULL AFTER  `to_user_id`
ALTER TABLE  `log` CHANGE  `publications_id`  `to_publication_id` INT( 11 ) NULL DEFAULT NULL
ALTER TABLE  `log` CHANGE  `operation`  `operation` ENUM(  'Add_admin',  'Edit_user',  'Delete_user',  'Add_publisher',  'Edit_publisher',  'Delete_publisher', 'Add_publication',  'Edit_publication',  'Delete_publication',  'Add_advertising',  'Edit_advertising',  'Delete_advertising' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
ALTER TABLE  `log` CHANGE  `advertising_id`  `to_advertising_id` INT( 11 ) NULL DEFAULT NULL