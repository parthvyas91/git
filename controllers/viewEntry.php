<?php

include_once("./models/entry_model.php");
include("./views/entryView.php");
	

class viewEntry
{
    public function __construct()
    {
	
        $this->model = new entry_model();    
    } 

 public function displayEntry($view)
    {
	
	

		
		$this->$view = new $view($view);
	    
    }
}

?>
