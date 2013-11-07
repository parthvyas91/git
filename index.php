<?php
/**
* index.php
*
* This file acts as entry point and calls appropriate controllers.
*
* 
*
*/

session_start();

require_once('./config/config.php');

$controllers_available= array('main','addEntry','viewEntry','updateEntry','gameDetailView');

//deciding the controller to be run
if(isset($_GET['c']) && in_array($_GET['c'],$controllers_available))
{
    if("main"==$_GET['c'])
	{
        $controller = "main";
    }
    else{
        $controller = $_GET['c'];
    }
}
else{
    $controller = "main";
}

$controller();
function main()
{
	
	
	if(isset($_GET['view']))
	{
	    	if("mainView"==$_GET['view'])
			{
			$view = "mainView";
		    	}
		 else
			{
			$view = $_GET['view'];
		   	}
	}
	else
{
	$view = "mainView";
}
require_once("./controllers/main.php");
	$entryControl = new main();
    $entryControl->display($view);
    
}
function addEntry()
{
	if(isset($_POST["title"]))
		{
			$title = $_POST["title"];
		}
	
		if(isset($_POST['view']))
		{
		    	if("mainView"==$_POST['view'])
				{
				$view = "mainView";
			    	}
			 else
				{
				$view = $_POST['view'];
			   	}
		}
		else
	{
	$view = "addView";
	}
	    

	if(isset($_POST['addGame']) && $_POST['addGame'] == "game")
	{
		require_once("./controllers/addEntry.php");
		$entryControl = new addEntry();
		$entryControl->addIntoEntry($_POST['title'],$_POST['gSerial'],$_POST['Price'],$_POST['Date'],$_POST['Stock'],$_POST['Sold']);
		require_once("./controllers/main.php");
    		$entryControl = new main();
    		$entryControl->display($view);
	}
	else if(isset($_POST['addGame']) && $_POST['addGame'] == "updategame")
	{
		require_once("./controllers/updateEntry.php");
		$entryControl = new updateEntry();
		$entryControl->updateIntoEntry($_POST['title'],$_POST['price']);
		require_once("./controllers/main.php");
		    $theview = new main();
		    $theview->display($view);
	}
	else
	{
    	    require_once("./controllers/addEntry.php");
	    $entryControl = new addEntry();
	    $entryControl->displayEntry($view);
	}

}
function viewEntry()
{
	
	
		if(isset($_POST['view']))
		{
		    	if("mainView"==$_POST['view'])
				{
				$view = "mainView";
			    	}
			 else
				{
				$view = $_POST['view'];
			   	}
		
		}
else
{
	$view = "entryView";
}
		require_once("./controllers/viewEntry.php");
	    $entryControl = new viewEntry();
	    $entryControl->displayEntry($view);
	    
}
function gameDescription()
{
	
	
		if(isset($_POST['view']))
		{
		    	if("mainView"==$_POST['view'])
				{
				$view = "mainView";
			    	}
			 else
				{
				$view = $_POST['view'];
			   	}
		
		}
else
{
	$view = "mainView";
}
	    require_once("./controllers/gameDetailEntry.php");
	    $entryControl = new gameDetailEntry();
	    $entryControl->displayEntry($view);
	    
}
function updateEntry()
{
	
	
		if(isset($_POST['view']))
		{
		    	if("mainView"==$_POST['view'])
				{
				$view = "mainView";
			    	}
			 else
				{
				$view = $_POST['view'];
			   	}
		
		}
else
{
	$view = "updateView";
}
		require_once("./controllers/updateEntry.php");
	    $entryControl = new updateEntry();
	    $entryControl->displayUpdatedEntry($view);
	    
}





?>
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">


<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title><?php echo SITENAME;?></title>
<meta name="Authors" content="Parth Vyas, Vinh Doan, Nicolas Seto" />
<meta name="description" content="Game Store" />
<meta name="keywords" content="Database" />
<meta charset="utf-8" />
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW"/>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL;?>css/styles.css" />
</head>
<body>	
</body>
</html>


