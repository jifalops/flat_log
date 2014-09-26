<?php
 	require_once('header.php');
		
	$response = array (
		10 => 'failed hash check.',
		20 => 'unknown request type.',
	);
		
		
    $json = stripslashes($_POST['request']);
    $hash = htmlspecialchars($_POST['hash']);
	
    $request = json_decode($json, true);
    $hash_check = md5($json);

	if ($hash_check != $hash) {
		die("10: {$response[10]}.");
	}
	
	$type = htmlspecialchars($request['type']);
	
	if ($type == 'ranging') {
		$r = new Ranging();
		$r->insert($request['data']);		
	} else if ($type == 'scheme') {
		$s = new Scheme();
		$s->insert($request['data']);
	} else {
		die("20: {$response[20]}.");
	}
	