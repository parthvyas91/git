<?php

include_once("./models/entry_model.php");
include("./views/gameDetailView.php");

class gameDetailEntry
{
    private model;
    
    public function __construct()
    {
        $this->model = new entry_model();    
    } 

    public function displayEntry($view,$title)
    {
        $this->$view = new $view($title);
    }
}
?>