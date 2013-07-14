SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE SCHEMA IF NOT EXISTS `mercatino` DEFAULT CHARACTER SET latin1 ;
USE `mercatino` ;

-- -----------------------------------------------------
-- Table `mercatino`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `role` ENUM('Admin','Basic','Publisher') NOT NULL ,
  `first_name` VARCHAR(50) NOT NULL ,
  `last_name` VARCHAR(50) NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL ,
  `is_publisher` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`states`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`states` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publishers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publishers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `publisher_type` ENUM('Person','Business') NOT NULL ,
  `seller_name` VARCHAR(80) NOT NULL ,
  `rif_ci` VARCHAR(20) NOT NULL ,
  `state_id` INT NOT NULL ,
  `city` VARCHAR(50) NOT NULL ,
  `phone1` VARCHAR(20) NOT NULL ,
  `phone2` VARCHAR(20) NULL ,
  `media` VARCHAR(150) NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `deleted_at` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_customers_users1_idx` (`user_id` ASC) ,
  UNIQUE INDEX `users_id_UNIQUE` (`user_id` ASC) ,
  INDEX `fk_publishers_state1_idx` (`state_id` ASC) ,
  CONSTRAINT `fk_publishers_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `mercatino`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publishers_states`
    FOREIGN KEY (`state_id` )
    REFERENCES `mercatino`.`states` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(50) NOT NULL ,
  `short_description` VARCHAR(50) NULL ,
  `long_description` VARCHAR(300) NULL ,
  `status` ENUM('Draft','Published','On_Hold','Suspended','Finished','Trashed') NOT NULL ,
  `from_date` DATE NOT NULL ,
  `to_date` DATE NOT NULL ,
  `remember` TINYINT(1) NOT NULL DEFAULT 0 ,
  `visits_number` INT NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL ,
  `publisher_id` INT NOT NULL ,
  `rating_avg` DECIMAL(3,2) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_publications_customers1_idx` (`publisher_id` ASC) ,
  CONSTRAINT `fk_publications_publishers`
    FOREIGN KEY (`publisher_id` )
    REFERENCES `mercatino`.`publishers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `category_id` INT NULL ,
  `slug` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) )
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`advertising`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`advertisings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `status` ENUM('Draft','Published','Trashed') NOT NULL ,
  `category_id` INT NOT NULL ,
  `image_url` VARCHAR(50) NOT NULL ,
  `external_url` VARCHAR(50) NULL ,
  `first_name` VARCHAR(50) NOT NULL ,
  `last_name` VARCHAR(50) NOT NULL ,
  `email` VARCHAR(50) NOT NULL ,
  `phone1` VARCHAR(20) NOT NULL ,
  `phone2` VARCHAR(20) NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_advertising_categories1_idx` (`category_id` ASC) ,
  CONSTRAINT `fk_advertising_categories`
    FOREIGN KEY (`category_id` )
    REFERENCES `mercatino`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`contacts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(50) NOT NULL ,
  `last_name` VARCHAR(50) NOT NULL ,
  `email` VARCHAR(50) NULL ,
  `phone` VARCHAR(20) NULL ,
  `publisher_id` INT NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_contacts_customers1_idx` (`publisher_id` ASC) ,
  CONSTRAINT `fk_contacts_publishers`
    FOREIGN KEY (`publisher_id` )
    REFERENCES `mercatino`.`publishers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publications_categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_categories` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `publication_id` INT NOT NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_publication_categories_publications1_idx` (`publication_id` ASC) ,
  INDEX `fk_publication_categories_categories1_idx` (`category_id` ASC) ,
  CONSTRAINT `fk_publications_categories_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publications_categories_categories`
    FOREIGN KEY (`category_id` )
    REFERENCES `mercatino`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publications_reports`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_reports` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `publication_id` INT NOT NULL ,
  `comment` VARCHAR(300) NOT NULL ,
  `date` DATETIME NOT NULL ,
  `status` ENUM('Pending','Correct','Incorrect') NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) ,
  INDEX `fk_publication_reports_publications1_idx` (`publication_id` ASC) ,
  INDEX `fk_publication_reports_users1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_publications_reports_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publications_reports_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `mercatino`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publications_ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_ratings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `publisher_id` INT NOT NULL ,
  `publication_id` INT NOT NULL ,
  `vote` INT NOT NULL ,
  `comment` VARCHAR(300) NULL ,
  `date` DATETIME NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) ,
  INDEX `fk_publication_raitings_customer_idx` (`publisher_id` ASC) ,
  INDEX `fk_publication_raitings_publications1_idx` (`publication_id` ASC) ,
  CONSTRAINT `fk_publications_raitings_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publications_raitings_publishers`
    FOREIGN KEY (`publisher_id` )
    REFERENCES `mercatino`.`publishers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`business_sectors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`business_sectors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`log` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `operation` ENUM('Edit_user','Delete_user','Add_admin','Edit_advertising','Delete_advertising','Add_advertising') NOT NULL ,
  `date` DATETIME NOT NULL ,
  `value_field` VARCHAR(50) NULL ,
  `previous_value` VARCHAR(50) NULL ,
  `final_value` VARCHAR(50) NULL ,
  `from_user_id` INT NULL ,
  `to_user_id` INT NULL ,
  `publications_id` INT NULL ,
  `advertising_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publishers_sectors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publishers_sectors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `publisher_id` INT NOT NULL ,
  `business_sector_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_customer_sectors_customers1_idx` (`publisher_id` ASC) ,
  INDEX `fk_customer_sectors_business_sectors1_idx` (`business_sector_id` ASC) ,
  CONSTRAINT `fk_publishers_sectors_publishers`
    FOREIGN KEY (`publisher_id` )
    REFERENCES `mercatino`.`publishers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publishers_sectors_business_sectors`
    FOREIGN KEY (`business_sector_id` )
    REFERENCES `mercatino`.`business_sectors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publishers_reports`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publishers_reports` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `publisher_id` INT NOT NULL ,
  `comment` VARCHAR(300) NOT NULL ,
  `date` DATETIME NOT NULL ,
  `status` ENUM('Pending','Correct','Incorrect') NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) ,
  INDEX `fk_customer_reports_from_user_idx` (`user_id` ASC) ,
  INDEX `fk_customer_reports_to_publisher_idx` (`publisher_id` ASC) ,
  CONSTRAINT `fk_publishers_reports_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `mercatino`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publishers_reports_publishers`
    FOREIGN KEY (`publisher_id` )
    REFERENCES `mercatino`.`publishers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;


-- -----------------------------------------------------
-- Table `mercatino`.`publications_images`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mercatino`.`publications_images` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_url` VARCHAR(50) NOT NULL ,
  `publication_id` INT NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`) ,
  INDEX `fk_publication_images_publications1_idx` (`publication_id` ASC) ,
  CONSTRAINT `fk_publications_images_publications`
    FOREIGN KEY (`publication_id` )
    REFERENCES `mercatino`.`publications` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = INNODB;

USE `mercatino` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
