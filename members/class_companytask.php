<?php

/* Defining the Task class */

class CompanyTask{
	
	/* An array that stores the task item data: */
	
	private $data;
	
	/* The constructor */
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the Task objects with echo. 
	*/
		
	public function __toString(){
		
		// The string we return is outputted by the echo statement
		return '
			<li id="companytask-'.$this->data['id'].'" class="companytask">
			
				<div class="text">'.$this->data['text'].'</div>
				
				<div class="actions">
					<a href="#" class="target">Target</a>
					<a href="#" class="edit">Edit</a>					
					<a href="#" class="delete">Delete</a>
				</div>
				
			</li>';
	}
	
	public function show_as_selected(){
		
		// The string we return is outputted by the echo statement
		return '
			<li id="companytask-'.$this->data['id'].'" class="companytask_selected">
			
				<div class="text">'.$this->data['text'].'</div>
				
				<div class="actions">
					<a href="#" class="target">Target</a>
					<a href="#" class="edit">Edit</a>					
					<a href="#" class="delete">Delete</a>
				</div>
				
			</li>';
	}
	
	public function show($field){
	
		$field = self::esc($field);
		if(!$field) throw new Exception("Wrong update field!");
		
		return $this->data[$field];
	}	
	
	/*
		The following are static methods. These are available
		directly, without the need of creating an object.
	*/
	
	
	
	/*
		The edit method takes the Task item id and the new text
		of the Task. Updates the database.
	*/
		
	public static function edit($id, $text){
	
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE companytasks
						SET text='".$text."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}
	
	/*
		The delete method. Takes the id of the Task item
		and deletes it from the database.
	*/
	
	public static function delete($id){
		mysql_query("DELETE FROM companytasks WHERE id=".$id);
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}
	
	/*
		The rearrange method is called when the ordering of
		the tasks is changed. Takes an array parameter, which
		contains the ids of the tasks in the new order.
	*/
	
	public static function rearrange($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the Task positions en masse:
		
		mysql_query("	UPDATE companytasks SET position = CASE id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	/*
		The createNew method takes only the text of the task,
		writes to the databse and outputs the new task back to
		the AJAX front-end.
	*/
	
	public static function createNew($text,$company_id){
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM companytasks WHERE company_id=$company_id");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		mysql_query("INSERT INTO companytasks SET text='".$text."', company_id = ".$company_id.", position = ".$position);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting TODO!");
		
		// Creating a new Task and outputting it directly:
		
		echo (new CompanyTask(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		exit;
	}

/* 	public static function Company_createNew($text){
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM companies");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		mysql_query("INSERT INTO companies SET text='".$text."', position = ".$position);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting TODO!");
		
		// Creating a new Task and outputting it directly:
		
		echo (new CompanyTask(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		exit;
	} */
	
	/*
		A helper method to sanitize a string:
	*/
	
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} // closing the class definition

?>