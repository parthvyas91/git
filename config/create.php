<?php

require_once('config.php');

$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD);



// Check connection
/*
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$sql = "DROP DATABASE GameStore";
if (mysqli_query($con,$sql)) {
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysqli_error() . "\n";
}
*/

//Create database
$sql="CREATE DATABASE GameStore";
if (mysqli_query($con,$sql)) 
{
    echo "Database my_db was successfully dropped\n";
} else 
{
    echo 'Error dropping database: ' . mysqli_error($con) . "\n";
}


$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");
// Create table

$sql="CREATE TABLE Game(Title CHAR(30),gSerial INT PRIMARY KEY,Price FLOAT, updatedAt INT,CONSTRAINT FOREIGN KEY(gSerial) REFERENCES Inventory(gSerial))";

$sqq = "CREATE TABLE Inventory(gSerial INT PRIMARY KEY,inStock INT DEFAULT 0,numSold INT DEFAULT 0)";

// Execute query
if (mysqli_query($con,$sqq)) {
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysqli_error($con) . "\n";
}
// Execute query
if (mysqli_query($con,$sql)) {
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysqli_error($con) . "\n";
}

$con=mysqli_connect("127.0.0.1",USERNAME,PASSWORD,"GameStore");

$q2 = "Insert into Game Values('Grand Theft Auto','123','49.50','1')";
$q1 = "Insert into Inventory Values('123','10','5')";
$q4 = "Insert into Game Values('SUPER MARIO','111','49.50','2')";
$q3 = "Insert into Inventory Values('111','10','5')";
$q6 = "Insert into Game Values('Pokemon','112','49.50','2')";
$q5 = "Insert into Inventory Values('112','10','5')";
$q8 = "Insert into Game Values('Final Fantasy','113','49.50','2')";
$q7 = "Insert into Inventory Values('113','10','5')";


if(mysqli_query($con,$q1))
{
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysqli_error($con) . "\n";
}

if(mysqli_query($con,$q2))
{
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysqli_error($con) . "\n";
}

mysqli_query($con,$q3);

mysqli_query($con,$q4);

mysqli_query($con,$q5);

mysqli_query($con,$q6);

mysqli_query($con,$q7);

mysqli_query($con,$q8);

?>
