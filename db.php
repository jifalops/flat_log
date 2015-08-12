<?php
  require_once('header.php');
  
  $table = htmlspecialchars($_GET['table']);
  $search = htmlspecialchars($_GET['q']);
  if (empty($table)) $table = 'all';
  
  require_once(INC_DIR.DS.'header.html');
?>

<form class='search' action='' method='get'>
	<input type='text' name='q' value='<?php echo $search; ?>' />
	<input type='submit' value='Search' />
</form>

<?php
  if ($table == 'all') {
    $tables = $db->get_tables();
    foreach ($tables as $table) {
      make_table($table);
    }
  }
  else {
    $log->v("showing '$table' table");
	  make_table($table);
  }

  function make_table($table) {

    $fields = $GLOBALS['db']->get_column_names($table);
    if (empty($fields)) {
      $GLOBALS['log']->e("table '$table' has no fields or doesn't exist");
    }

    $sql = "SELECT * FROM `$table`";
    if (!empty($GLOBALS['search'])) {
    	$sql .= " WHERE ";
    	$first = true;
    	foreach ($fields as $field) {
    		if (!$first) $sql .= " OR ";
    		else $first = false;
    		$sql .= "`$field` LIKE '%".$GLOBALS['db']->escape($GLOBALS['search'])."%'";
    	}
    }

    $records = $GLOBALS['db']->query($sql);

    echo '<h3>'.$table.'</h3>';
    if ($table == 'device') {
      echo '<a href="edit.php">add new device</a><br />'.NL;
    }
    if ($table == 'mac') {
      echo '<a href="edit.php">assign devices</a><br />'.NL;
    }
    echo '<table border="1"><tr>'.NL;
    foreach ($fields as $field) {
      echo '<th>'.$field.'</th>'.NL;
    }
    echo '</tr>'.NL;
    if (is_array($records)) {
      foreach ($records as $record) {
        echo '<tr>'.NL;
        foreach ($record as $item) {
          $item = highlight($GLOBALS['search'], $item);
          echo '<td>'.$item.'</td>'.NL;
        }
        echo '</tr>'.NL;
      }
    }
    else $GLOBALS['log']->d("table '$table' is empty");
    echo '</table>'.NL;
  }
  
  function highlight($needle, $haystack) {
    $pos = 0;
    $before = '<span class="search-result">';
    $after = '</span>';
    while($pos < strlen($haystack)) {
      // $GLOBALS['log']->d("hay: $haystack");
      $pos = stripos($haystack, $needle, $pos);
      if ($pos === false) break;
      $haystack = substr_replace($haystack, $before, $pos, 0); // insert
      $pos += strlen($before) + strlen($needle);
      $haystack = substr_replace($haystack, $after, $pos, 0); // insert
      $pos += strlen($after);
    }
    return $haystack;
  }

  require_once(INC_DIR.DS.'footer.html');
