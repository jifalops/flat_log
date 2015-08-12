<?php
  require_once('header.php');
  
  define('REQ_TYPE', 'requestType');
  define('TYPE_MAC', 'mac');
  define('TYPE_RSSI', 'rssi');
  
  define('RESP_HASH_FAILED', 10);
  define('RESP_UNKNOWN_REQ_TYPE', 20);
  define('RESP_INSERT_FAILED', 30);
  define('RESP_ALREADY_EXISTS', 31);
  define('RESP_INTEGRITY_FAILED', 40);
  define('RESP_SUCCESS', 200);
 		
 	$responses = array (
		RESP_HASH_FAILED => 'failed hash check.',
		RESP_UNKNOWN_REQ_TYPE => 'unknown request type.',
		RESP_INSERT_FAILED => 'failed adding record to database.',
		RESP_ALREADY_EXISTS => 'record already exists',
		RESP_INTEGRITY_FAILED => 'database integrity compromised',
		RESP_SUCCESS => 'Success.',
	);
  
  /*
   * $_POST = array(
   *   REQ_TYPE => type
   *   '0' => item 0 (jsonObjectString)
   *   '1' => item 1 (jsonObjectString)
   *   ...
   * );
   */
  
  $type = htmlspecialchars($_POST[REQ_TYPE]);
  unset($_POST[REQ_TYPE]);
  
  $items = array();
  for ($i = 0, $len = count($_POST); $i < $len; $i++) {
    $json = $_POST[$i];
    $item = json_decode($json, true);
    // $log->d('Item = ' . nl2br(print_r($item, true)));
    $items[] = $item;
  }
  
	$response = RESP_SUCCESS;
	$result = '';
 	
 	if ($type == TYPE_MAC) {
    for ($i = 0, $len = count($items); $i < $len; $i++) {
      $item = $items[$i];
      $id = get_mac_id($item['mac']);
   	  if (!$id) {
     	  $result = insert_mac($item);
   	  } else {
   	    $response = RESP_ALREADY_EXISTS;
   	  }
    }
 	} else if ($type == TYPE_RSSI) {
 	  for ($i = 0, $len = count($items); $i < $len; $i++) {
      $item = $items[$i];
   	  $lid = get_mac_id($item['localMac']);
   	  $rid = get_mac_id($item['remoteMac']);
   	  
   	  if (!$rid) {
   	    $req = array();
   	    $req['mac'] = $item['remoteMac'];
   	    $req['desc'] = $item['remoteDesc'];
   	    $rid = insert_mac($req);
   	  }
   	  
   	  $item['local_mac_id'] = $lid;
   	  $item['remote_mac_id'] = $rid;
   	  
   	  $result = insert_rssi($item);
 	  }
 	} else {
 	  $response = RESP_UNKNOWN_REQ_TYPE;
 	}
 	
 	
 	echo '{"responseCode":'.$response.',"responseMessage":"'.$responses[$response].'","queryResult":"'.$result.'"}';
 	
 	
 	
 	
 	function get_mac_id($mac) {
 	  // $GLOBALS['log']->d('MAC = ' . nl2br(print_r($mac, true)));
    return $GLOBALS['db']->result('SELECT `id` FROM `mac` WHERE `mac`="'.$GLOBALS['db']->escape($mac).'"');
  }
  
  function insert_mac($info) {
    $req = array();
    $mac = new Mac();
    foreach (array_keys($mac->fields()) as $f) {
      // $GLOBALS['log']->d('MAC FIELD = ' . nl2br(print_r($info[$f], true)));
      $req[$f] = $GLOBALS['db']->escape($info[$f]);
 	  }
    return $GLOBALS['db']->result($mac->insert($req));
  }
  
  function insert_rssi($info) {
    $req = array();
    $rssi = new Rssi();
    foreach (array_keys($rssi->fields()) as $f) {
      // $GLOBALS['log']->d('RSSI FIELD = ' . nl2br(print_r($info[$f], true)));
      $req[$f] = $GLOBALS['db']->escape($info[$f]);
 	  }
    return $GLOBALS['db']->result($rssi->insert($req));
  }