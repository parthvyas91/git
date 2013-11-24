<?php
require_once('views/baseview.php'); 
class mainView extends baseview
{
    public function __construct()
    {      
?>
        <h1>Game Store</h1>
        <div id="mainWrapper">
        <div id="leftWrapper">
       
        
    <!-- action="index.php?c=poem" -->
        <form name="viewGames" action="index.php?c=viewEntry" method="post">
        View Game List
    <input type="hidden" name="a" value="entryView">
        <input type="submit" value="Submit">
        </form>
        
      
    <!-- action="index.php?c=poem" -->
        <form name="addGames" action="index.php?c=addEntry" method="post">
    
        Add Into Game List 
    <input type="hidden" name="a" value="addEntry">
        <input type="submit" value="Submit">
    
        </form>
        
        

    <!-- action="index.php?c=poem" -->
        <form name="updateGame" action="index.php?c=updateEntry" method="post">
        Update Game Info
    <input type="hidden" name="a" value="updateView">
        <input type="submit" value="Submit">
        </form>
        
        </div>  
        </div>  
    
        
              
    <?php
    }     
}
?>