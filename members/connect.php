<?php

// include the config
require_once('../config/config.php');

/* Database config */

$db_host		= DB_HOST;
$db_user		= DB_USER;
$db_pass		= DB_PASS;
$db_database	= DB_NAME; 

/* End config */


$link = @mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');
mysql_set_charset('utf8');
mysql_select_db($db_database,$link);

?>