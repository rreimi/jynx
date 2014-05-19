-- -----------------------------------------------------
-- Table `mercatino`.`groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `allow_new_members` ENUM('Active','Inactive') NOT NULL ,
  PRIMARY KEY (`id`))
ENGINE = INNODB;

INSERT INTO  `mercatino`.`groups` (`id` ,`name` ,`allow_new_members`) VALUES ('1',  'CAVENIT',  'Active');

ALTER TABLE `users` ADD `group_id` int NOT NULL DEFAULT 1 AFTER `step`;
ALTER TABLE `users` ADD FOREIGN KEY ( `group_id` )
 REFERENCES `mercatino`.`groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
