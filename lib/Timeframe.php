<?php
class Timeframe extends DatabaseTable {
    function table_name() { return 'timeframe'; }
	function fields() {
		return array(				
		'node_time'		 => 'bigint NOT NULL',
		'node_datetime'	 => 'varchar(50) NOT NULL',
		'db_time'		 => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');		
	}
	
	function insert($record) {
		unset($record['db_time']);
		parent::insert($record);
	}	
}