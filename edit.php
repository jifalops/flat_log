<?php
  require_once('header.php');

  require_once(INC_DIR.DS.'header.html');
  
  $dev = array();
  $mac_dev_id = array();
  
  $dev['name'] = htmlspecialchars($_GET['name']);
  $dev['desc'] = htmlspecialchars($_GET['desc']);
  
  foreach (array_keys($_GET) as $key) {
    $key = htmlspecialchars($key);
    if (!is_array($key) && substr($key, 0, 10) == 'device_id_') {
      $mac_dev_id[substr($key, 10)] = htmlspecialchars($_GET[$key]);
    }
  }
  
  if ($dev['name']) {
    $d = new Device();
    $dev['name'] = $db->escape($dev['name']);
    $dev['desc'] = $db->escape($dev['desc']);
    $db->query($d->insert($dev));
  } elseif (count($mac_dev_id) > 0) {
    foreach (array_keys($mac_dev_id) as $mac_id) {
      $db->query('UPDATE `mac` SET `device_id`='.$db->escape($mac_dev_id[$mac_id]).' WHERE `id`='.$db->escape($mac_id));
    }
  }
  
  function make_table($table) {
    $fields = $GLOBALS['db']->get_column_names($table);
    if (empty($fields)) {
      $GLOBALS['log']->e("table '$table' has no fields or doesn't exist");
    } else {
      $sql = "SELECT * FROM `$table`";
      if ($table == 'mac') {
        $sql .= ' WHERE `device_id`=0';
      }
      $records = $GLOBALS['db']->query($sql);
  
      if ($table == 'mac') {
        echo '<form>'.NL;
      }
      echo '<h3>'.$table.'</h3>';
      echo '<table border="1"><tr>'.NL;
      foreach ($fields as $field) {
        echo '<th>'.$field.'</th>'.NL;
      }
      echo '</tr>'.NL;
      if (is_array($records)) {
        foreach ($records as $record) {
          echo '<tr>'.NL;
          if ($table == 'mac') {
            $record['device_id'] = '<input type="text" name="device_id_'.$record['id'].'" size="3" value="'.$record['device_id'].'" />';
          }
          foreach ($record as $item) {
            echo '<td>'.$item.'</td>'.NL;
          }
          echo '</tr>'.NL;
        }
      }
      else $GLOBALS['log']->d("table '$table' is empty");
      echo '</table>'.NL;
      if ($table == 'mac') {
        echo '<input type="submit" value="Associate devices" /></form>'.NL;
      }
    }
  }
?>

<h3>Add new device</h3>
<form>
  Name (up to 20 chars):<br />
  <input type="text" name="name" /><br />
  Description (up to 255 chars):</br />
  <textarea name="desc" rows="5" cols="30"></textarea><br />
  <input type="submit" value="Add New" />
</form>

<?php make_table('device'); ?>

<hr />
<h3>Associate MAC and device</h3>

<?php
  make_table('mac');
  require_once(INC_DIR.DS.'footer.html');