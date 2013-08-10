DROP TABLE `mercatino`.`publications_ratings`;

-- -----------------------------------------------------
-- Table `mercatino`.`publications_ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_ratings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `publication_id` INT NOT NULL ,
  `vote` INT NOT NULL ,
  `comment` VARCHAR(300) NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_publication_raitings_user_idx` (`user_id` ASC) ,
  INDEX `fk_publication_raitings_publication_idx` (`publication_id` ASC) ,
  CONSTRAINT `fk_publications_raitings_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publications_raitings_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `mercatino`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;