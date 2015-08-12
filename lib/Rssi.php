<?php
class Rssi extends DatabaseTable {
  function table_name() { return 'rssi'; }
	function fields() {
		return array(
		'id'               => 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
		'local_mac_id'  => 'int NOT NULL',
		'remote_mac_id' => 'int NOT NULL',
		'rssi'		         => 'double NOT NULL',
		'method'           => 'varchar(30) NOT NULL',
		'distance'		       => 'double NOT NULL',
		'db_time'		       => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
	}
	
// 	function extra_keys() {
// 	  return array(
// 	    'FOREIGN KEY (`local_mac_id`) REFERENCES mac(`id`)',
// 	    'FOREIGN KEY (`remote_mac_id`) REFERENCES mac(`id`)',
//     );
// 	}
	
	function insert($record) {
		unset($record['db_time'], $record['id']);
		return parent::insert($record);
	}
}
