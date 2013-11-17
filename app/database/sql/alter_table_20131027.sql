ALTER TABLE  `log` CHANGE  `publications_id`  `to_publication_id` INT( 11 ) NULL DEFAULT NULL
ALTER TABLE  `log` CHANGE  `previous_value`  `previous_value` VARCHAR( 1000 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
ALTER TABLE  `log` CHANGE  `final_value`  `final_value` VARCHAR( 1000 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
ALTER TABLE  `log` CHANGE  `advertising_id`  `to_advertising_id` INT( 11 ) NULL DEFAULT NULL
ALTER TABLE  `log` ADD  `to_publisher_id` INT( 11 ) NULL AFTER  `to_user_id`
ALTER TABLE  `log` ADD  `to_rating_id` INT( 11 ) NULL AFTER  `to_advertising_id`
ALTER TABLE  `log` ADD  `to_publication_image_id` INT( 11 ) NULL AFTER `to_publication_id`
ALTER TABLE  `log` ADD  `to_report_id` INT( 11 ) NULL AFTER  `to_publication_image_id`
ALTER TABLE  `log` CHANGE  `operation`  `operation` ENUM(  'Add_admin',  'Edit_user',  'Delete_user',  'Add_publisher',  'Edit_publisher',  'Delete_publisher', 'Add_publication',  'Edit_publication',  'Delete_publication',  'Add_publication_image',  'Delete_publication_image',  'Add_advertising',  'Add_advertising_image', 'Edit_advertising',  'Delete_advertising',  'Delete_advertising_image',  'Approve_publisher',  'Disapprove_publisher',  'Valid_report',  'Invalid_report',  'Active_rating', 'Inactive_rating' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

