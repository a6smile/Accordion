<?php

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));  

if (!function_exists('db_add_field')) {
	function db_add_field($field, $table, $desc) {
		global $database;
		$query = $database->query("DESCRIBE $table '$field'");
		if(!$query || $query->numRows() == 0) { // add field
			$query = $database->query("ALTER TABLE $table ADD $field $desc");
			echo (mysql_error()?mysql_error().'<br />':'');
			$query = $database->query("DESCRIBE $table '$field'");
			echo (mysql_error()?mysql_error().'<br />':'');
		}
	}
}
 
// Safely add field that was added after v0.5
$table_data = TABLE_PREFIX."mod_accordion_settings";
db_add_field("icon_placement", $table_data, "TEXT NOT NULL");
?>