ALTER TABLE  `groups` CHANGE  `allow_new_members`  `status` ENUM(  'Active',  'Inactive' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE  `groups` CHANGE  `name`  `group_name` VARCHAR( 80 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE  `groups` ADD  `created_at` DATETIME NOT NULL AFTER  `status`;

ALTER TABLE  `groups` ADD  `updated_at` DATETIME NOT NULL;

ALTER TABLE  `groups` ADD  `deleted_at` DATETIME NULL;

ALTER TABLE  `users` CHANGE  `group_id`  `group_id` INT( 11 ) NULL DEFAULT  '1'

ALTER TABLE  `users` CHANGE  `role`  `role` ENUM(  'Admin',  'SubAdmin',  'Basic',  'Publisher' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE  `log` CHANGE  `operation`  `operation` ENUM(  'Add_admin',  'Edit_user',  'Delete_user',  'Add_publisher',  'Edit_publisher',  'Delete_publisher', 'Add_publication',  'Edit_publication',  'Delete_publication',  'Add_publication_image',  'Delete_publication_image',  'Add_advertising',  'Add_advertising_image', 'Edit_advertising',  'Delete_advertising',  'Delete_advertising_image',  'Approve_publisher',  'Disapprove_publisher',  'Valid_report',  'Invalid_report',  'Active_rating', 'Inactive_rating', 'Add_group',  'Edit_group',  'Delete_group', ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
