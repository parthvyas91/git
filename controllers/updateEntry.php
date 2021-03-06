<?php

include_once("./models/entry_model.php");
include("./views/updateView.php");

class updateEntry
{
    public function __construct()
    {
        $this->model = new entry_model();    
    } 
    
    public function displayUpdatedEntry($view)
    {
        $this->$view = new $view($view);
    }
    
    public function updateIntoEntry($title,$price)
    {
        $this->model = new entry_model();
        $this->model->update_Game($title,$price);
    }
}
?>