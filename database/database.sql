-- MySQL Script generated by MySQL Workbench
-- mar 31 mar 2020, 09:42:24
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';



-- -----------------------------------------------------
-- Table `covid_regions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covid_regions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covid_provinces`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covid_provinces` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(70) NOT NULL,
  `code` CHAR(2) NOT NULL,
  `region` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_covid_provinces_covid_regions1_idx` (`region` ASC),
  CONSTRAINT `fk_covid_provinces_covid_regions1`
    FOREIGN KEY (`region`)
    REFERENCES `covid_regions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covid_regional_trend`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covid_regional_trend` (
  `region` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `intensive_care_unit` INT UNSIGNED NOT NULL,
  `hospitalized_no_icu` INT UNSIGNED NOT NULL,
  `isolation` INT UNSIGNED NOT NULL,
  `positives` INT UNSIGNED NOT NULL,
  `cures` INT UNSIGNED NOT NULL,
  `dead` INT UNSIGNED NOT NULL,
  `swabs` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`region`, `date`),
  INDEX `fk_covid_regions_has_covid_dates_covid_regions_idx` (`region` ASC),
  CONSTRAINT `fk_covid_regions_has_covid_dates_covid_regions`
    FOREIGN KEY (`region`)
    REFERENCES `covid_regions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covid_provincial_trend`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covid_provincial_trend` (
  `province` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `cases` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`province`, `date`),
  INDEX `fk_covid_dates_has_covid_provinces_covid_provinces1_idx` (`province` ASC),
  CONSTRAINT `fk_covid_dates_has_covid_provinces_covid_provinces1`
    FOREIGN KEY (`province`)
    REFERENCES `covid_provinces` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
