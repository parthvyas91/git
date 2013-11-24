<?php
//require_once("./views/mainView.php");
class entry_model{
     public function get_GameList()
    {
$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
	// Check connection
	if (mysqli_connect_errno($con))
  	{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
	$sql = "Select Title From Game";

	$result = mysqli_query($con,$sql);
	$string= array();
	if ($result)
  	{
	while($row = mysqli_fetch_array($result))
	  {
	  array_push($string,$row['Title'],"<br>");
	 }
	return $string;
	}
	else
	  {
	  return "Error creating database: " . mysqli_error($con);
	  }

    }
    public function get_GameDetail($title)
    {
$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
	// Check connection
	if (mysqli_connect_errno($con))
  	{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
	$sql = "Select Title,Price From Game Where Title='$title'";

	$result = mysqli_query($con,$sql);
	$string= array();
	if ($result)
  	{
	while($row = mysqli_fetch_array($result))
	  {
	  array_push($string,$row['Title'],$row['Price'],"<br>");
	 }
	return $string;
	}
	else
	  {
	  return "Error creating database: " . mysqli_error($con);
	  }

    }
	public function insert_Game($title,$gSerial,$price,$date)
	    {
		$gserial = intval($gSerial);
		$Price = floatval($price);
		$Date = intval($date);

	$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
		// Check connection
		if (mysqli_connect_errno($con))
	  	{
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  	}

	$sql = "INSERT INTO Game (Title,gSerial,Price,updatedAt) VALUES ('$title','$gserial', '$Price','$Date')";

	mysqli_query($con,$sql);
	

	    }
	public function insert_Inventory($gSerial,$stock,$sold)
	    {
		$gserial = intval($gSerial);
		$Stock = intval($stock);
		$Sold = intval($sold);

	$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
		// Check connection
		if (mysqli_connect_errno($con))
	  	{
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  	}

	$sql = "INSERT INTO Inventory (gSerial,inStock,numSold) VALUES ('$gserial','$Stock','$Sold')";


		if (!mysqli_query($con,$sql))
	  {
	  
		return 'false';
	  }
	else
	{
	
	return 'true';
	}

	    }
	public function update_Game($title,$price)
	    {
		$newprice = intval($price);

	$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
		// Check connection
		if (mysqli_connect_errno($con))
	  	{
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  	}

	$sql = "UPDATE Game SET Price='$newprice' WHERE Title='$title'";

	mysqli_query($con,$sql);
	

	    }

   
}
?>
