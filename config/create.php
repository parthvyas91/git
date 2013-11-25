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
$create_games = "CREATE TABLE `Games`(`Title` VARCHAR(40) NOT NULL,`GSerial` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`Price` FLOAT(2,2) NOT NULL,`updatedAt` DATETIME) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$create_inventory = "CREATE TABLE `Inventory`(`GSerial` INT UNSIGNED NOT NULL PRIMARY KEY,`InStock` INT NOT NULL DEFAULT 0,`NumSold` INT NOT NULL DEFAULT 0,CONSTRAINT FOREIGN KEY(`GSerial`) REFERENCES `Games`(`GSerial`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$create_gamedetails = "CREATE TABLE `GameDetails`(`GSerial` INT UNSIGNED NOT NULL PRIMARY KEY, `Genre` VARCHAR(10) NOT NULL, `ESRBRating` VARCHAR(3) NOT NULL, `GameScore` INT, `Year` YEAR NOT NULL, CONSTRAINT FOREIGN KEY(`GSerial`) REFERENCES `Games`(`GSerial`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$create_customers = "CREATE TABLE `Customers`(`FirstName` VARCHAR(15) NOT NULL, `MidInitial` VARCHAR(1), `LastName` VARCHAR(15) NOT NULL, `Email` VARCHAR(50) NOT NULL, `Age` INT NOT NULL, `CustomerID` INT UNSIGNED NOT NULL AUTO_INCREMENT, `Gender` CHAR(1), PRIMARY KEY(`CustomerID`,`Email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; //Make sure to check email doesn't exist in relation
$create_orders = "CREATE TABLE `Orders`(`OrderID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `CustomerID` INT UNSIGNED NOT NULL, `GSerial` INT UNSIGNED NOT NULL, `NumBought` INT NOT NULL, CHECK (`CustomerID` IN (SELECT `CustomerID` FROM `Customers`)), CONSTRAINT `instock` CHECK (`NumBought` <= (SELECT `InStock` FROM `Inventory` WHERE `Inventory`.`GSerial` = `GSerial`))) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
// Execute multi_query
if (mysqli_query($con, $create_games)) {
    echo "Successfully created table Games...\n";
} else {
    die("Error creating Games: " . mysqli_error($con) . "\n");
}
if (mysqli_query($con, $create_inventory)) {
    echo "Successfully created table Inventory...\n";
} else {
    die("Error creating Inventory: " . mysqli_error($con) . "\n");
}
if (mysqli_query($con, $create_gamedetails)) {
    echo "Successfully created table GameDetails...\n";
} else {
    die("Error creating GameDetails: " . mysqli_error($con) . "\n");
}
if (mysqli_query($con, $create_customers)) {
    echo "Successfully created table Customers...\n";
} else {
    die("Error creating Customers: " . mysqli_error($con) . "\n");
}
if (mysqli_query($con, $create_orders)) {
    echo "Successfully created table Orders...\n";
} else {
    die("Error creating Orders: " . mysqli_error($con) . "\n");
}

$orderTrigger = "DROP TRIGGER IF EXISTS `Order`;
DELIMITER // 
CREATE TRIGGER `Order` 
AFTER INSERT ON `Orders` 
FOR EACH ROW 
BEGIN
UPDATE `Inventory` SET InStock = InStock - NEW.NumBought, NumSold = NumSold + NEW.NumBought 
WHERE Inventory.GSerial = NEW.GSerial
END //
DELIMITER ;";

if (mysqli_multi_query($con, $orderTrigger)) {
    echo "Successfully created Order trigger...\n";
} else {
    die("Error creating Order trigger (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

$cancelOrderTrigger = "DROP TRIGGER IF EXISTS 'CancelledOrder';
DELIMITER //
CREATE TRIGGER `CancelledOrder` 
AFTER DELETE ON `Orders` 
FOR EACH ROW 
BEGIN 
UPDATE `Inventory` SET InStock = InStock + OLD.NumBought, NumSold = NumSold - OLD.NumBought 
WHERE Inventory.GSerial = OLD.GSerial
END //
DELIMITER ;";

if (mysqli_multi_query($con, $cancelOrderTrigger)) {
    echo "Successfully created cancelOrderTrigger trigger...\n";
} else {
    die("Error creating cancelOrder trigger (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

$listByGenre = "DELIMITER //
CREATE PROCEDURE `listGamesByGenre`(IN `g` VARCHAR(40)) 
BEGIN 
SELECT `Title`, `Year`, `Genre`, `Price` FROM `Games` INNER JOIN `GameDetails` using(`GSerial`) WHERE Genre=g 
ORDER BY `Title` DESC 
END //
DELIMITER ;";

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