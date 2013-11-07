<?php

include_once("./models/entry_model.php");
include_once("./views/entryView.php");
include_once("./views/addPoem.php");
include_once("./views/addView.php");

class addEntry
{
    public function __construct()
    {
	
        $this->model = new entry_model();    
    } 

 public function displayEntry($view)
    {
	include("./views/mainView.php");
		$this->$view = new $view();
	    
    }
  public function addIntoEntry($title,$gserial,$price,$date,$stock,$sold)
	{
	//include("./views/mainView.php");
	
	//$this->entry->displayEntry('mainView');
	$this->model = new entry_model();
	$this->model->insert_Inventory($gserial,$stock,$sold);
	$this->model->insert_Game($title,$gserial,$price,$date);
	
	
	}
}

?>
