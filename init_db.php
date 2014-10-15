<?php
 	require_once('header.php');
	
	$confirm = htmlspecialchars($_GET['confirm']);
	
	$r = new Range();
	$s = new Scheme();
	$t = new Timeframe();
	
	
	require_once(INC_DIR.DS.'header.html');
	
	if (!empty($confirm)) {
		$db->query($r->drop_table());		
		$db->query($r->create_table());
		
		$db->query($s->drop_table());
		$db->query($s->create_table());
		
		$db->query($t->drop_table());		
		$db->query($t->create_table());
?>

<div id='title'>Database Initialized.</div>

<?php
	} else {	
?>
Dropping tables isn't working for some reason, this will only create tables.
<div id='title'>
Initialize Database?
<form><input name='confirm' type='submit' value='Confirm' /></form>
</div>

<?php
	}
