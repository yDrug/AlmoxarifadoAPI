-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema almoxarifado
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema almoxarifado
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `almoxarifado` DEFAULT CHARACTER SET utf8 ;
USE `almoxarifado` ;

-- -----------------------------------------------------
-- Table `almoxarifado`.`almMarca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `almoxarifado`.`almMarca` (
  `idMarca` INT NOT NULL,
  `nmMarca` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idMarca`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `almoxarifado`.`almProduto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `almoxarifado`.`almProduto` (
  `idProduto` INT NOT NULL,
  `nmProduto` VARCHAR(100) NOT NULL,
  `deProduto` VARCHAR(500) NULL,
  `idMarca` INT NOT NULL,
  `cdProduto` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idProduto`),
  INDEX `fk_almProduto_almMarca_idx` (`idMarca` ASC) VISIBLE,
  CONSTRAINT `fk_almProduto_almMarca`
    FOREIGN KEY (`idMarca`)
    REFERENCES `almoxarifado`.`almMarca` (`idMarca`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `almoxarifado`.`almEntradaEstoque`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `almoxarifado`.`almEntradaEstoque` (
  `idEntrada` INT NOT NULL AUTO_INCREMENT,
  `dtEntrada` DATE NOT NULL,
  `qtEntrada` DECIMAL(8,2) NOT NULL,
  `vlEntrada` DECIMAL(12,2) NULL,
  `idProduto` INT NOT NULL,
  `idUsuario` VARCHAR(100) NULL,
  PRIMARY KEY (`idEntrada`),
  INDEX `fk_almEntradaEstoque_almProduto1_idx` (`idProduto` ASC) VISIBLE,
  CONSTRAINT `fk_almEntradaEstoque_almProduto1`
    FOREIGN KEY (`idProduto`)
    REFERENCES `almoxarifado`.`almProduto` (`idProduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
