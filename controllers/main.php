<?php
include_once("./models/entry_model.php");
include("./views/mainView.php");
include("./views/gameDetailView.php");

class main{
    public function __construct()
    {
        
    }
    
    public function display($view)
    {
        $this->$view = new $view();
    }
}
?>