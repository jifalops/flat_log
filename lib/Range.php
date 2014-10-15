<?php
class Range extends DatabaseTable {
    function table_name() { return 'ranging'; }
	function fields() {
		return array(
		'node_id' 		 => 'char(17) NOT NULL',
		'remote_node_id' => 'char(17) NOT NULL',
		'algorithm'		 => 'varchar(20) NOT NULL',
		'estimate'		 => 'double NOT NULL',
		'actual'		 => 'double DEFAULT NULL',
		'node_time'		 => 'bigint NOT NULL',
		'db_time'		 => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
	}
	
	function insert($record) {
		unset($record['db_time']);
		parent::insert($record);
	}	
}