<?php
/*
 * header.php
 * The purpose of this file is to be included at the beginning
 * of every PHP file (except classes, which go in the library
 * directory). That will ensure that pages within the site work
 * consistently. To include it, the preferred method would be:
 *
 *  require_once(PATH_TO_THIS_FILE);
 */

define('APP_ROOT', __DIR__);					// Required before loading the framework.
require_once('../framework/framework.php');		// Load the framework.
error_reporting(E_ALL & ~E_NOTICE);

// Include our private data (not part of the public code, assuming it is in .gitignore).
require_once(INTERNAL_DIR.DS.'Internal.php');
						  
$db = new DatabaseHelper(   Internal::DB_HOST,      Internal::DB_USERNAME,
							Internal::DB_PASSWORD,  Internal::DB_DATABASE,
							LOG_DIR.DS.'db_log.txt');

// Create database tables if necessary (the DB itself and login credentials must be known ahead of time).
$r = new Rssi();
$m = new Mac();
$d = new Device();
if (!$db->table_exists($d->table_name())) {
	$db->query($d->create_table());
}
if (!$db->table_exists($m->table_name())) {
	$db->query($m->create_table());
}
if (!$db->table_exists($r->table_name())) {
	$db->query($r->create_table());
}
unset($r, $m, $d);
												
// Logging mechanism for developers. Attempts to be similar to Android's logging mechanism.
$log = new Log(LOG_DIR.DS.'dev_log.txt');
