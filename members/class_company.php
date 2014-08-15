<?php

/* Defining the Company class */

class Company{
	
	/* An array that stores the company item data: */
	
	private $data;
	
	/* The constructor */
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the Company objects with echo. 
	*/
		
	public function __toString(){
		
		// The string we return is outputted by the echo statement
		return '
			<li id="company-'.$this->data['id'].'" class="company">
			
				<div class="text">'.$this->data['text'].'</div>
				
				<div class="actions">
					<a href="#" class="target_same_window">Target same window</a>
					<a href="#" class="target_new_window">Target new window</a>
					<a href="#" class="edit">Edit</a>					
					<a href="#" class="delete">Delete</a>
				</div>
				
			</li>';
	}

	public function for_menu(){
		
		// The string we return is outputted by the echo statement
		return '
			<li id="company-'.$this->data['id'].'" class="company">
				<div class="back_actions">
					<a href="#" class="back_to_companylist">Back</a>					
					<a href="#" class="edit"></a>						
				</div>

				<div class="back_to_companylist">COMPANY: '.$this->data['text'].'</div>				
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
		The edit method takes the Company item id and the new text
		of the Company. Updates the database.
	*/
		
	public static function edit($id, $text){
	
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE companies
						SET text='".$text."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}
	
	/*
		The delete method. Takes the id of the Company item
		and deletes it from the database.
	*/
	
	public static function delete($id){
		// PRIMERO SE MIRA SI TIENE TAREAS COLGANDO
		$posResult = mysql_query("SELECT * FROM companytasks WHERE company_id=$id");
		
		if(mysql_num_rows($posResult)==0){		
			mysql_query("DELETE FROM companies WHERE id=".$id);
			if(mysql_affected_rows($GLOBALS['link'])!=1){
				throw new Exception("Couldn't delete item!");
				$return_arr["status"]='error';
				$return_arr["message"]=utf8_encode("Error");				
			} else {
				$return_arr["status"]='done';
				$return_arr["message"]=utf8_encode("");
			}
		} else {
			//throw new Exception("Couldn't delete company! Please, delete company tasks before deleting company.");		
			$return_arr["status"]='error';
			$return_arr["message"]=utf8_encode(mysql_num_rows($posResult)." task associated to this company. Please, remove them before deleting Company.");
		}
		
		echo json_encode($return_arr);
		exit();				
	}
	
	/*
		The rearrange method is called when the ordering of
		the companys is changed. Takes an array parameter, which
		contains the ids of the companys in the new order.
	*/
	
	public static function rearrange($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the Company positions en masse:
		
		mysql_query("	UPDATE companies SET position = CASE id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	/*
		The createNew method takes only the text of the company,
		writes to the databse and outputs the new company back to
		the AJAX front-end.
	*/
	
	public static function createNew($text){
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM companies");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		mysql_query("INSERT INTO companies SET text='".$text."', position = ".$position);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting COMPANY!");
		
		// Creating a new Company and outputting it directly:
		
		echo (new Company(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		exit;
	}
	
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