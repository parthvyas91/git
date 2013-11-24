<?php
require_once('views/baseview.php'); 
class gameDetailView extends baseview { 
    public function __construct($title)
    {      
?>  
        <h1>Game Details</h1>
        
        
             
    <?php    
    $this->model = new entry_model();
    $Games = $this->model->get_GameDetail($title);

    $this->view = new baseview();

    $this->view->display($Games);
?>


<?php
    }
}   
?>