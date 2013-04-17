<?php
//Handles connection to KRUI DJ database
class Database {
	function connect() {
		$host='localhost'; // Host name.
		$db_user='kruifm_staff'; // MySQL username.
		$db_password='rt06vlditkrui897'; // MySQL password.
		$database='kruifm_staff'; // Database name.
		mysql_connect($host,$db_user,$db_password) or die('MySQL Error: '.mysql_error());
		mysql_select_db($database);
	}
}
?>