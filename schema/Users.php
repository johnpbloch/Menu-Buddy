<?php

if( !isset( $this ) )
	return;

$schema = "CREATE TABLE `{$this->dbname}`.`{$this->$table}` (
	`ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`Login` varchar(60) NOT NULL,
	`Email` varchar(100) NOT NULL,
	`DisplayName` varchar(60) NOT NULL,
	`DateCreated` datetime NOT NULL,
	`Pass` varchar(64) NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE KEY `Login` (`Login`),
	UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM;";

$this->query( $schema );
