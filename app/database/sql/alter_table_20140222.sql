ALTER TABLE  `publications_reports` CHANGE  `status`  `status` ENUM(  'Pending',  'Valid',  'Invalid' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE  `publications_reports` ADD  `final_status_date` DATETIME NOT NULL AFTER  `status`

UPDATE `publications_reports` SET `status`= 'Valid' WHERE `status`= 'Correct';
UPDATE `publications_reports` SET `status`= 'Invalid' WHERE `status`= 'Incorrect';

