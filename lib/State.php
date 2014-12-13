<?php
class State extends DatabaseTable {
    function table_name() { return 'state'; }
	function fields() {
		return array(
		'node_id' 		 => 'char(17) NOT NULL',		
		'remote_node_id'	 => 'char(17) NOT NULL',
		'algorithm'		 => 'varchar(20) NOT NULL',				
		'x'			 => 'double NOT NULL',
		'y'			 => 'double NOT NULL',
		'z'			 => 'double NOT NULL',
		'a'			 => 'double NOT NULL',
		'b'			 => 'double NOT NULL',
		'c'			 => 'double NOT NULL',
		'node_time'		 => 'bigint NOT NULL',
		'node_datetime'	 	 => 'varchar(50) NOT NULL',
		'db_time'		 => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
	}
	
	function insert($record) {
		unset($record['db_time']);
		parent::insert($record);
	}
}
