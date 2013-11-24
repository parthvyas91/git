<?php

require_once('config.php');

$con=mysqli_connect($conf['db_host'],$conf['db_user'],$conf['db_pass']);

// Check connection
if (mysqli_connect_errno($con))
{
    die("Failed to connect to MySQL: " . mysqli_connect_error() . "\n");
}

// Create a fresh database
$query = "DROP DATABASE IF EXISTS GameStore;";
if (mysqli_query($con,$query)) {
    echo "Database my_db was successfully dropped\n";
} else {
    die('Error dropping database: ' . mysqli_error() . "\n");
}

//Create database
$query="CREATE DATABASE GameStore;";
if (mysqli_query($con,$query)) 
{
    echo "Database GameStore was successfully created.\n";
} else {
    die('Error dropping database: ' . mysqli_error($con) . "\n");
}

// Use GameStore database
mysqli_select_db($con, 'GameStore');

// Create tables
$create_tables= <<<EOF
CREATE TABLE Games(Title VARCHAR(40) NOT NULL,GSerial INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,Price FLOAT(2,2) NOT NULL,updatedAt DATETIME);
CREATE TABLE Inventory(GSerial INT UNSIGNED NOT NULL PRIMARY KEY,InStock INT NOT NULL DEFAULT 0,NumSold INT NOT NULL DEFAULT 0,CONSTRAINT FOREIGN KEY(GSerial) REFERENCES Game(GSerial));
CREATE TABLE GameDetails(GSerial INT UNSIGNED NOT NULL PRIMARY KEY, Genre VARCHAR(10) NOT NULL, ESRBRating VARCHAR(3) NOT NULL, GameScore INT, Year YEAR NOT NULL, CONSTRAINT FOREIGN KEY(GSerial) REFERENCES Game(GSerial));
CREATE TABLE Customers(FirstName VARCHAR(15) NOT NULL, MidInitial VARCHAR(1), LastName VARCHAR(15) NOT NULL, Email VARCHAR(50) NOT NULL PRIMARY KEY, Age INT NOT NULL, CustomerID INT UNSIGNED NOT NULL AUTO_INCREMENT, Gender CHAR(1), CHECK (Email NOT IN (SELECT Email in Customers)));
CREATE TABLE OrdersPlaced(OrderID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, CustomerID INT UNSIGNED NOT NULL, GSerial INT UNSIGNED NOT NULL, NumBought INT NOT NULL, CHECK (CustomerID IN (SELECT CustomerID FROM Customers)), CHECK (NumBought <= (SELECT InStock IN Inventory WHERE Inventory.GSerial = GSerial)));
EOF;

// Execute multi_query
if (mysqli_multi_query($con, $create_tables)) {
    echo "Successfully created tables Games, Inventory, GameDetails, Customers, and OrdersPlaced...\n";
} else {
    die("Error creating tables:" . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

/*
$q2 = "Insert into Games(Title, Price, updatedAt) Values('Grand Theft Auto V','54.50','". date("Y-m-d H:i:s") ."')";
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
*/
?>