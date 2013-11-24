<?php
    require_once('views/baseview.php');
    class updateView extends baseview{ 
    public function __construct()
    {
?>
        <h1>Add Game</h1>
        <div id="mainWrapper">
        <div id="leftWrapper">
        
        

    <!-- action="index.php?c=poem" -->
        <form name="updateGame" action="index.php?c=main" method="post">
        Update Game
    <input type="hidden" name="addGame" value="updategame">
    Game Title: <input type="text" name="title">
    <br>
    New Price: <input type="text" name="price">
    <br>
    
        <input type="submit" value="Submit">
        </form>
        
        </div>  
        </div>  
    
        
              
<?php
    }
}
?>