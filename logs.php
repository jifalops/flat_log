<?php	
	require_once('header.php');

	define('ERROR_LOG', ini_get('error_log'));

	$dev_level = htmlspecialchars($_GET['dev_level']);
    $db_level = htmlspecialchars($_GET['db_level']);
	$clear = htmlspecialchars($_GET['clear']);
	
	if (empty($dev_level)) $dev_level = LOG::MAX_LEVEL;	
    if (empty($db_level)) $db_level = LOG::MAX_LEVEL;	

	
	if ($clear == 'dev_level') {
		$file = $log->get_filename(); 
		if (file_exists($file)) {
			unset($log);
			unlink($file);
			header('Location: logs.php?dev_level='.$dev_level);
			die();
		}
	}
    elseif ($clear == 'db_level') {
        $file = $db->get_log()->get_filename(); 
		if (file_exists($file)) {
			//unset($db->get_log());
			unlink($file);
			header('Location: logs.php?db_level='.$db_level);
			die();
		}
    }
	elseif (!empty($clear) && substr($clear, 0 - strlen(ERROR_LOG)) == ERROR_LOG && file_exists($clear)) {		
		unlink($clear);
		header('Location: logs.php');
		die();
	}
	
	
	
	
	
	function make_log_header($title, $name) { 
        echo "<h3>$title</h3>
        <a href='?clear=$name'>clear</a><br />
        <p><a href='?$name=".LOG::LEVEL_ASSERT."'>assert</a>
        | <a href='?$name=".LOG::LEVEL_ERROR."'>error</a>
        | <a href='?$name=".LOG::LEVEL_WARNING."'>warning</a>
        | <a href='?$name=".LOG::LEVEL_INFO."'>info</a>
        | <a href='?$name=".LOG::LEVEL_DEBUG."'>debug</a>
        | <a href='?$name=".LOG::LEVEL_VERBOSE."'>verbose</a></p>";
    }

	function get_error_logs_recursive($dir=APP_ROOT) {
		// File system objects
        $fsos = scandir($dir);
        foreach ($fsos as $fso) {
			if ($fso == '.' || $fso == '..') continue;
            $fso_full = $dir.DS.$fso;    
            if (is_file($fso_full) && $fso == ERROR_LOG) {
                $logs[] = $fso_full;
            }
            // Recurse, skip hidden directories (ones that start with a dot)
			// and skip the private dir
            else if (is_dir($fso_full) 
					&& substr($fso, 0, 1) != '.'
					&& $fso_full != INTERNAL_DIR) {
                $subdir_errors = get_error_logs_recursive($fso_full);
				if (!empty($subdir_errors)) {
					foreach ($subdir_errors as $e) {
						if (!empty($e)) $logs[] = $e;
					}
				}
            }	
        }
		return $logs;
	}
	
	
	
	

	require_once(INC_DIR.DS.'header.html');
    
    
    echo make_log_header('Developer Log', 'dev_level');
    echo $log->to_html($dev_level);
	echo '<hr />';
    echo make_log_header('Database Log', 'db_level');
    echo $db->get_log()->to_html($db_level);
	echo '<hr />';
    
    echo '<h3>MySqli Warnings</h3>'.NL;
	$warnings = $db->get_warnings();
	foreach ($warnings as $w) {
	    echo 'Warning '.$w['errno'].': '.$w['message'].'<br />'.NL;
	}
    
	echo '<h3>PHP Error Logs:</h3>'.NL;
	$logs = get_error_logs_recursive();
	if (is_array($logs)) {
		foreach ($logs as $l) {
			echo '<h4>'.$l.':</h4>'.NL;
			echo '<a href="logs.php?clear='.urlencode($l).'">clear</a><br />'.NL;	
			echo nl2br(file_get_contents($l));
		}
	}
	
	
	require_once(INC_DIR.DS.'footer.html');

    
