<?php
class Device extends DatabaseTable {
  function table_name() { return 'device'; }
	function fields() {
		return array(
		'id'       => 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
		'name' 	   => 'varchar(20) NOT NULL UNIQUE',
		'desc'     => 'varchar(255) NOT NULL');
	}
	
	function insert($record) {
		unset($record['id']);
		return parent::insert($record);
	}
}
