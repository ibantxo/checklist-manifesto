<?php

/**
 * A simple PHP Login Script / ADVANCED VERSION
 * For more versions (one-file, minimal, framework-like) visit http://www.php-login.net
 *
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('../libraries/password_compatibility_library.php');
}
// include the config
require_once('../config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('../translations/en.php');

// include the PHPMailer library
require_once('../libraries/PHPMailer.php');

// load the login class
require_once('../classes/Login.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() != true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    //include("../views/logged_in.php");
    include("../views/not_logged_in.php");
	exit();
}



$selected_companytask_id = (int)$_GET['companytask_id'];
$debug = (int)$_GET['debug'];


require "connect.php";
require "class_companysubtask.php";
	
			
//SUBTASKS
$data='';

if ($selected_companytask_id>=0){
	$query_companysubtasks = mysql_query("SELECT * FROM `companysubtasks` WHERE companytask_id=$selected_companytask_id ORDER BY `position` ASC");
	$companysubtasks = array();
	// Filling the $companys array with new Company objects:
	while($row_companysubtasks = mysql_fetch_assoc($query_companysubtasks)){
		$companysubtasks[$row_companysubtasks['id']] = new CompanySubTask($row_companysubtasks);
	}	
	
	

		foreach($companysubtasks as $item_companysubtask){
			if ($selected_companysubtask_id>0 && $item_companysubtask->show('id')==$selected_companysubtask_id){
				$data.= $item_companysubtask->show_as_selected();
			} else {
				$data.= $item_companysubtask;
			}
		}


	//$data.= "<a id=\"addButton_SubTask\" class=\"green-button\" href=\"#\">++ subtask</a>";							
} 

$return_arr["status"]='done';
$return_arr["message"]=utf8_encode($selected_companytask_id);	
$return_arr["data"].= $data;

if ($debug){
	echo $data;	
} else {
	echo json_encode($return_arr);	
}
	
exit;			
			

?>

