<?php

if(!isset ($this))
	return;

$schema = "CREATE TABLE `{$this->dbname}`.`{$this->$table}` (
	`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(100) NOT NULL,
	`DisplayName` VARCHAR(60) NOT NULL,
	`Description` MEDIUMTEXT NOT NULL DEFAULT '',
	`Type` VARCHAR(16) NOT NULL,
	`Active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	`Dependencies` VARCHAR(255) NOT NULL DEFAULT '',
	`ControllerFile` VARCHAR(500) NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE KEY `Name`(`Name`),
	UNIQUE KEY `ControllerFile`(`ControllerFile`)
)
ENGINE = MyISAM;";

$this->query( $schema );
