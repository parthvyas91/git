<?php
class baseview {
    function display($Games) {
?>
    <div id="mainWrapper">
        
                
        <?php
        echo "<br>";
        foreach($Games as $value)
        {?>
        <a href="index.php?c=gameDescription&view=gameDetailView&game=<?php echo $value;?>"> <?php echo $value; ?> </a>
        
        <?php
        }       
        ?>
        
          
    </div>
<?php
    }
}
?>