<?php

if(!isset($this))
	return;

$schema = "CREATE TABLE `{$this->dbname}`.`{$this->$table}` (
  `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Login` VARCHAR(60)  NOT NULL,
  `Email` VARCHAR(100)  NOT NULL,
  `DisplayName` VARCHAR(60)  NOT NULL,
  `DateCreated` DATETIME  NOT NULL,
  `Pass` VARCHAR(64)  NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `Login`(`Login`),
  INDEX `Email`(`Email`)
)
ENGINE = MyISAM;";

$this->query($schema);
