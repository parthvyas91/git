<?php

include_once("./models/entry_model.php");

class gameDetailEntry
{
    public function __construct()
    {
	
        $this->model = new entry_model();    
    } 

 public function displayEntry($view)
    {
	//include("./views/mainView.php");
	//include("./views/addPoem.php");
	include("./views/gameDetailView.php");
	
        //$this->$view = new $view($poem);
	

		
		$this->$view = new $view($view);
	    
    }
}

?>
