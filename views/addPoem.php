<?php
    require_once('views/baseview.php'); 
    class addPoem extends baseview{ 
    public function __construct()
    {        
        
        ?>
        <h1>Add Poem</h1>
        <div id="mainWrapper">
        <div id="leftWrapper">
        
        <!-- action="index.php?c=poem" -->
        <form name="myForm" action="index.php?c=poem" onsubmit="return validate();" 
        method="post">
        <input type="hidden" name="view" value="mainView" />
        Title: <input type="text" name="title"/><br/>
        Author: <input type="text" name="author"/><br/>
        Poem: <br/>
        <textarea rows="5" cols="30" name="poem"></textarea>
        <input type="submit" value="Submit">
        </form>
        
        </div>  
        </div>        
<?php    
	$this->model = new entry_model();
	$Games = $this->model->get_GameList();
	$topRated = $this->model->get_toprated();

    	$this->view = new baseview();

        $this->view->display($Games,$topRated);       
    }     
   
    }   
    

    
?>
<script type="text/javascript">
function validate()
{
    var title=document.forms["myForm"]["title"].value;
    var author=document.forms["myForm"]["author"].value;
    var retStat = true;
    if(title.length > 30 || author.length > 30){
        alert("title or author cannot be more than 30 characters!");
        retStat = false;
    }
    
    var poem = document.forms["myForm"]["poem"].value;
    var poemArray = poem.split("\n");
    //var rhymeArray = new Array();
    if(poemArray.length > 5) returnStat = false;
    
     

    
    for(var i = 0; i < poemArray.length; i++){       
        if(poemArray[i].length > 30){
            retStat = false;
            break;
        }
        
    }    
    return retStat;    
        
     /*   
        else{
            poemArray[i].replace("\n", "");
            var words = poemArray[i].split(" ");
            var lastWord = words[words.length - 1];
            document.writeln(lastWord);
            var rhythm = <?php metaphone(lastWord) ?>;
            document.writeln(rhythm);
            //rhymeArray.push(rhythm);
        }
        
    }
    */
    /*
    for(var j = 0; j < rhymeArray.length; j++)
        document.writeln(rhymeArray[j]);
    */
}
</script>



