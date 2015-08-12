<?php
class Mac extends DatabaseTable {
  function table_name() { return 'mac'; }
	function fields() {
		return array(
		'id'       => 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
		'device_id' => 'int',
		'mac' 	   => 'char(17) NOT NULL UNIQUE',
		'desc'     => 'varchar(255) NOT NULL');
	}
	
	function insert($record) {
		unset($record['id']);
		return parent::insert($record);
	}
}
