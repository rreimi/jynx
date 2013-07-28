ALTER TABLE `publishers` ADD `letter_rif_ci` VARCHAR( 1 ) NOT NULL AFTER `seller_name`;
ALTER TABLE `users` ADD `status` ENUM('Admin','Basic','Publisher') NOT NULL AFTER `password`;
ALTER TABLE `categories` ADD `type` ENUM('Product','Service') NOT NULL AFTER `category_id`;
DROP TABLE `publishers_reports`;
ALTER TABLE `categories` ADD INDEX ( `category_id` ) ;

ALTER TABLE `categories` ADD CONSTRAINT `fk_publishers_categories_categories`
    FOREIGN KEY (`category_id` )
    REFERENCES `mercatino`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

-- -----------------------------------------------------
-- Table `mercatino`.`publications_contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_contacts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `publication_id` INT NOT NULL ,
  `contact_id` INT NOT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_publications_contacts_contact_idx` (`contact_id` ASC) ,
  INDEX `fk_publications_contacts_publication_idx` (`publication_id` ASC) ,
  CONSTRAINT `fk_publications_contacts_contacts`
    FOREIGN KEY (`contact_id` )
    REFERENCES `mercatino`.`contacts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publications_contacts_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

