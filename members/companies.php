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


$selected_company_id = (int)$_GET['company_id'];
$UserOption = $_REQUEST['UserClicked'];
$Id = $_REQUEST['Id'];

echo $UserOption;



require "connect.php";
require "class_company.php";
require "class_companytask.php";
require "class_companysubtask.php";


// Select all the companys, ordered by position:
$query = mysql_query("SELECT * FROM `companies` ORDER BY `position` ASC");

$companys = array();

// Filling the $companys array with new Company objects:

while($row = mysql_fetch_assoc($query)){
	$companys[$row['id']] = new Company($row);
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Including our scripts -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="script.js"></script>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>
<?php
if ($selected_company_id>0){
	echo $companys[$selected_company_id]->show('text');
} else {
	echo "ENPRESAK";
}
?>
</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

<script>
var currentCOMPANY_id = "<?php  echo $selected_company_id;  ?>";
</script>

</head>

<body>

<?php
if ($selected_company_id>0){
	echo "<h1><ul class=\"\">".$companys[$selected_company_id]->for_menu()."</h1>";
} else {
	echo "<h1>ENPRESAK</h1>";
}
?>


<!-- <h2><a href="http://tutorialzine.com/2010/03/ajax-todo-list-jquery-php-mysql-css/">Go Back to the Tutorial &raquo;</a></h2> -->

<div id="main">


		
        <?php
		
		// Looping and outputting the $companys array. The __toString() method
		// is used internally to convert the objects to strings:
		
		if ($selected_company_id>0){
			//EMPRESA SELECCIONADA
			
			//MOSTRAR TAREAS DE ESA EMPRESA
			// Select all the companys, ordered by position:
			print "<table id=\"main_table\" border=1>";
			print "<tr>";
			
			
			//TASKS
			print "    <td>";
			$query_companytasks = mysql_query("SELECT * FROM `companytasks` WHERE company_id=$selected_company_id ORDER BY `position` ASC");
			$companytasks = array();
			// Filling the $companys array with new Company objects:
			while($row_companytasks = mysql_fetch_assoc($query_companytasks)){
				$companytasks[$row_companytasks['id']] = new CompanyTask($row_companytasks);
			}	
			
			print "<ul class=\"companyTaskList\">";
			foreach($companytasks as $item_companytask){
				if ($selected_companytask_id>0 && $item_companytask->show('id')==$selected_companytask_id){
					echo $item_companytask->show_as_selected();
				} else {
					echo $item_companytask;
				}
			}
			echo "</ul>";
			print "<a id=\"addButton_Task\" class=\"green-button\" href=\"#\">+ task</a>";				
			print "    </td>";
			
			//SUBTASKS
			print "    <td>";			
			print "<ul id=\"companySubTaskList\" class=\"companySubTaskList\">";
			echo "</ul>";			
			print "<a id=\"addButton_SubTask\" class=\"green-button\" href=\"#\">++ subtask</a>";
			print "    </td>";			
			
			
			
			print "</tr>";
			print "</table>";
		
			
		} else {
			//EMPRESA NO SELECCIONADA
			print "<ul class=\"companyList\">";
			foreach($companys as $item_company){
				echo $item_company;
			}
			echo "</ul>";
			print "<a id=\"addButton_Company\" class=\"green-button\" href=\"#\">+ enpresa</a>";
		}
		
		
		?>





</div>

<!-- This div is used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->
<div id="dialog-confirm-company" title="Delete COMPANY Item?">Are you sure you want to delete this COMPANY item?</div>

<!-- This div is used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->
<div id="dialog-confirm-company-task" title="Delete COMPANY TASK Item?">Are you sure you want to delete this COMPANY TASK item?</div>

<!-- This div is used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->
<div id="dialog-confirm-company-task-subtask" title="Delete COMPANY TASK SUBTASK Item?">Are you sure you want to delete this COMPANY TASK SUBTASK item?</div>




</body>
</html>
