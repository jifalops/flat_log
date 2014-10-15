<?php
 	require_once('header.php');

print_r($_POST);	
die();

	$response = array (
		10 => 'failed hash check.',
		20 => 'unknown request type.',
		30 => 'failed adding record to database.',
		201 => 'Success.',
	);
		
	$json = stripslashes($_POST['request']);
    $hash = htmlspecialchars($_POST['hash']);
	
    $request = json_decode($json, true);
    $hash_check = Internal::hash($json);

	if ($hash_check != $hash) {
		die("10: {$response[10]}.");
	}
	
	$table = htmlspecialchars($request['request']);
	
	if ($table == 'ranging') {
		$r = new Ranging();
		if ($r->insert($request['data'] === false) {
			die("30: {$response[30]}.");
		}
	} else if ($table == 'scheme') {
		$s = new Scheme();
		if ($s->insert($request['data'] === false) {
			die("30: {$response[30]}.");
		}
	} else {
		die("20: {$response[20]}.");
	}
	
	
	
	
	die("201: {$response[201]}.");