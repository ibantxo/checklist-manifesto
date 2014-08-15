<?php

require "connect.php";
require "todo.class.php";
require "class_company.php";
require "class_companytask.php";
require "class_companysubtask.php";


$id = (int)$_GET['id'];

try{

	switch($_GET['action'])	{
		case 'delete':
			ToDo::delete($id);
			break;
		
		case 'delete company':
			Company::delete($id);
			break;

		case 'delete company task':
			CompanyTask::delete($id);
			break;		
			
		case 'delete company task subtask':
			CompanySubTask::delete($id);
			break;					
			
		case 'rearrange':
			ToDo::rearrange($_GET['positions']);
			break;

		case 'rearrange company':
			Company::rearrange($_GET['positions']);
			break;

		case 'rearrange company task':
			CompanyTask::rearrange($_GET['positions']);
			break;

		case 'rearrange company task subtask':
			CompanySubTask::rearrange($_GET['positions']);
			break;
			
		case 'edit':
			ToDo::edit($id,$_GET['text']);
			break;

		case 'edit company':
			Company::edit($id,$_GET['text']);
			break;			

		case 'edit company task':
			CompanyTask::edit($id,$_GET['text']);
			break;	
		case 'edit company task subtask':
			CompanySubTask::edit($id,$_GET['text']);
			break;	
			
		case 'new':
			ToDo::createNew($_GET['text']);
			break;
		
		case 'new company':
			Company::createNew($_GET['text']);
			break;

		case 'new task for company':
			CompanyTask::createNew($_GET['text'],$_GET['company_id']);
			break;	

		case 'edit task for company':
			CompanyTask::edit($id,$_GET['text']);
			break;			
			
		case 'new subtask for task of company':
			CompanySubTask::createNew($_GET['text'],$_GET['company_id'],$_GET['companytask_id']);
			break;				
			
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

echo "1";
?>