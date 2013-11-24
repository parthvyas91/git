<?php

include_once("./models/entry_model.php");
include_once("./views/entryView.php");
include_once("./views/addView.php");


class addEntry
{
    public function __construct()
    {
	
        $this->model = new entry_model();    
    } 

 public function displayEntry($view,$checkerror)
    {
	
	include_once("./views/mainView.php");
	$this->view = new $view($checkerror);
	    
    }
  public function addIntoEntry($title,$gserial,$price,$date,$stock,$sold)
	{
	$this->model = new entry_model();
	return $errorMessage = $this->model->insert_Inventory($gserial,$stock,$sold);
	$this->model->insert_Game($title,$gserial,$price,$date);
	echo $errorMessage;
	
	
	}
}

?>
