<?php
    require_once('views/baseview.php'); 
    class addView extends baseview{ 
    public function __construct()
    {      
?>  
        <h1>Add Game</h1>
        <div id="mainWrapper">
        <div id="leftWrapper">
        
        

	<!-- action="index.php?c=poem" -->
        <form name="inputGame" action="index.php?c=addEntry" method="post">
        Insert New Game
	<input type="hidden" name="addGame" value="game">
	Title: <input type="text" name="title">
	Game Serial Number: <input type="text" name="gSerial">
	Price: <input type="text" name="Price">
	<br>
	Date: <input type="text" name="Date">
<br>
	Stock: <input type="text" name="Stock">
<br>
	Sold: <input type="text" name="Sold">
<br>
<?php
?>
	<input type="hidden" name="view" value="addView">
        <input type="submit" value="Submit">
        </form>
        
        </div>  
        </div>  
	
        
              
	<?php    
	
	

	
	}  
   
    }   
    

    
?>
