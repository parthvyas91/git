<?php
    require_once('views/baseview.php'); 
    class gameDetailView extends baseview{ 
    public function __construct()
    {      
?>  
        <h1>Game List</h1>
        
        
             
	<?php    
	$this->model = new entry_model();
	$Games = $this->model->get_GameList();

    	$this->view = new baseview();

        $this->view->display($Games);
?>


<?php 
      
    }     
   
    }   
    

    
?>
