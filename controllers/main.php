<?php
include_once("./models/entry_model.php");

class main{
    public function __construct()
    {
        
    }
    
    public function display($view)
    {
	include("./views/mainView.php");
	
        $this->$view = new $view();
    }
	

    
}
?>
