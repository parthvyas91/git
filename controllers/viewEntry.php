<?php

include_once("./models/entry_model.php");

class viewEntry
{
    public function __construct()
    {
	
        $this->model = new entry_model();    
    } 

 public function displayEntry($view)
    {
	//include("./views/mainView.php");
	//include("./views/addPoem.php");
	include("./views/entryView.php");
	
        //$this->$view = new $view($poem);
	

		
		$this->$view = new $view($view);
	    
    }
}

?>
