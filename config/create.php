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
$create_games = "CREATE TABLE `Games`(`Title` VARCHAR(40) NOT NULL,`GSerial` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,`Price` FLOAT(10,2) NOT NULL,`updatedAt` DATETIME) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
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
CREATE TRIGGER `Order` 
AFTER INSERT ON `Orders` 
FOR EACH ROW 
BEGIN
UPDATE `Inventory` SET InStock = InStock - NEW.NumBought, NumSold = NumSold + NEW.NumBought 
WHERE Inventory.GSerial = NEW.GSerial;
END;";

if (mysqli_multi_query($con, $orderTrigger)) {
    echo "Successfully created Order trigger...\n";
} else {
    die("Error creating Order trigger (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$cancelOrderTrigger = "DROP TRIGGER IF EXISTS `CancelledOrder`;
CREATE TRIGGER `CancelledOrder` 
AFTER DELETE ON `Orders` 
FOR EACH ROW 
BEGIN 
UPDATE `Inventory` SET InStock = InStock + OLD.NumBought, NumSold = NumSold - OLD.NumBought 
WHERE Inventory.GSerial = OLD.GSerial;
END;";

if (mysqli_multi_query($con, $cancelOrderTrigger)) {
    echo "Successfully created cancelOrderTrigger trigger...\n";
} else {
    die("Error creating cancelOrder trigger (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listByGenre = "DROP PROCEDURE IF EXISTS `listGamesByGenre`;
CREATE PROCEDURE `listGamesByGenre`(IN `g` VARCHAR(40)) 
BEGIN 
SELECT `Title`, `Year`, `Genre`, `Price` FROM `Games` INNER JOIN `GameDetails` using(`GSerial`) WHERE Genre=g 
ORDER BY `Title` DESC;
END;";

if (mysqli_multi_query($con, $listByGenre)) {
    echo "Successfully created listGamesByGenre procedure...\n";
} else {
    die("Error creating listGamesByGenre procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listByESRB = "DROP PROCEDURE IF EXISTS `listGamesByESRB`;
CREATE PROCEDURE `listGamesByESRB`(IN `rating` VARCHAR(3))
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM `Games` INNER JOIN `GameDetails` using(`GSerial`)
WHERE ESRBRating <> rating
ORDER BY Title ASC;
END;";

if (mysqli_multi_query($con, $listByESRB)) {
    echo "Successfully created listGamesByESRB procedure...\n";
} else {
    die("Error creating listGamesByESRB procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

// Regular query
$listByPopularity = "DROP PROCEDURE IF EXISTS `listGamesByPopularity`;
CREATE PROCEDURE `listGamesByPopularity`()
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM (`Games` INNER JOIN `Inventory` ON Games.GSerial=Inventory.GSerial)
INNER JOIN GameDetails ON Games.GSerial=GameDetails.GSerial
ORDER BY NumSold DESC;
END;";

if (mysqli_multi_query($con, $listByPopularity)) {
    echo "Successfully created listGamesByPopularity procedure...\n";
} else {
    die("Error creating listGamesByPopularity procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

// Regular query
$listByRating = "Select Title , GameScore
FROM Games `g` JOIN GameDetails `gd` on g.GSerial = gd.GSerial
ORDER BY GameScore DESC;";

$listByYear = "DROP PROCEDURE IF EXISTS `listGamesAfterYear`;
CREATE PROCEDURE `listGamesAfterYear`(IN yearMade YEAR)
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM Games `g` INNER JOIN GameDetails `gd` using(GSerial)
WHERE Year >= yearMade;
END;";

if (mysqli_multi_query($con, $listByYear)) {
    echo "Successfully created listGamesAfterYear procedure...\n";
} else {
    die("Error creating listGamesAfterYear procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listByLess = "DROP PROCEDURE IF EXISTS `listGamesUnder`;
CREATE PROCEDURE `listGamesUnder`(IN `thePrice` FLOAT(2,2))
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM Games `g` INNER JOIN GameDetails `gd` using(GSerial)
WHERE price <= thePrice;
END;";

if (mysqli_multi_query($con, $listByLess)) {
    echo "Successfully created listGamesUnder procedure...\n";
} else {
    die("Error creating listGamesUnder procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$cancelOrder = "DROP PROCEDURE IF EXISTS `cancelOrder`;
DELIMITER ;;
CREATE PROCEDURE `cancelOrder`(IN `order` INT UNSIGNED)
BEGIN
DELETE FROM `Orders`
WHERE OrderID = order
LIMIT 1;
END
;;
DELIMITER ;";

if (mysqli_multi_query($con, $cancelOrder)) {
    echo "Successfully created cancelOrder procedure...\n";
} else {
    die("Error creating cancelOrder procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$checkSold = "DROP PROCEDURE IF EXISTS `copiesSold`;
CREATE PROCEDURE `copiesSold`(IN `theTitle` VARCHAR(40))
BEGIN
SELECT Title, NumSold, GameScore
FROM (Games `g` INNER JOIN Inventory `i` ON g.GSerial = i.GSerial)
INNER JOIN GameDetails `gd` ON g.GSerial = gd.GSerial
WHERE Title = theTitle;
END;";

if (mysqli_multi_query($con, $checkSold)) {
    echo "Successfully created copiesSold procedure...\n";
} else {
    die("Error creating copiesSold procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$inStock = "DROP PROCEDURE IF EXISTS `copiesInStock`;
CREATE PROCEDURE `copiesInStock`(IN `theTitle` VARCHAR(40))
BEGIN
SELECT InStock
FROM Games `g` JOIN Inventory `i` ON g.GSerial = i.GSerial
WHERE Title = theTitle;
END;";

if (mysqli_multi_query($con, $inStock)) {
    echo "Successfully created copiesInStock procedure...\n";
} else {
    die("Error creating copiesInStock procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listByAgeAndGender = "DROP PROCEDURE IF EXISTS `AgeAndGender`;
CREATE PROCEDURE `AgeAndGender` (IN `birth` INT, IN `g` CHAR(1))
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM (`Customers` INNER JOIN `Orders` using(CustomerID)
WHERE Customers.Gender=g AND birth >= Customers.Age - 3 AND birth <= Customers.Age + 3) AS `c` 
INNER JOIN (`Games` INNER JOIN `GameDetails` using(GSerial)) AS `t`
ON c.GSerial = t.GSerial;
END;";

if (mysqli_multi_query($con, $listByAgeAndGender)) {
    echo "Successfully created AgeAndGender procedure...\n";
} else {
    die("Error creating AgeAndGender procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listByAge = "DROP PROCEDURE IF EXISTS `listGamesByAge`;
CREATE PROCEDURE `listGamesByAge` (IN `a` INT)
BEGIN
SELECT Title, Genre, ESRBRating, GameScore, Price
FROM (Games INNER JOIN Orders using(GSerial)) AS `w`
INNER JOIN Customers ON w.GSerial = Customers.GSerial
WHERE Age = a;
END;";

if (mysqli_multi_query($con, $listByAge)) {
    echo "Successfully created listGamesByAge procedure...\n";
} else {
    die("Error creating listGamesByAge procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$insertGame = "DROP PROCEDURE IF EXISTS `insert`;
CREATE PROCEDURE `insert` (IN `t` VARCHAR(40), IN `p` FLOAT(10,2), IN `u` DATETIME)
BEGIN
INSERT INTO `Games`(`Title`,`Price`,`updatedAt`) VALUES(`t`,`p`,`u`);
END;";

if (mysqli_multi_query($con, $insertGame)) {
    echo "Successfully created insert procedure...\n";
} else {
    die("Error creating insert procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

// Query to get GSerial of newly inserted game
/*$getID = "SELECT GSerial FROM Games WHERE Title = t;";
$result = mysqli_query($con, $getID);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);*/

$insertGameInventory = "DROP PROCEDURE IF EXISTS `inven`;
CREATE PROCEDURE `inven` (IN `g` INT UNSIGNED, IN `i` INT)
BEGIN
INSERT INTO `Inventory`(`GSerial`,`InStock`) VALUES(`g`,`i`);
END;";

if (mysqli_multi_query($con, $insertGameInventory)) {
    echo "Successfully created inven procedure...\n";
} else {
    die("Error creating inven procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$insertGameDetails = "DROP PROCEDURE IF EXISTS `det`;
CREATE PROCEDURE `det` (IN `g` INT UNSIGNED, `gen` VARCHAR(10), `esrb` VARCHAR(3), `y` YEAR)
BEGIN
INSERT INTO `GameDetails`(`GSerial`,`Genre`,`ESRBRating`,`Year`) VALUES (`g`,`gen`,`esrb`,`y`);
END;";

if (mysqli_multi_query($con, $insertGameDetails)) {
    echo "Successfully created det procedure...\n";
} else {
    die("Error creating det procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$updateScore = "DROP PROCEDURE IF EXISTS `updateScore`;
CREATE PROCEDURE `updateScore` (IN `g` INT UNSIGNED, IN `score` INT)
BEGIN
UPDATE `GameDetails` SET GameScore = score
WHERE GSerial = g;
END;";

if (mysqli_multi_query($con, $updateScore)) {
    echo "Successfully created updateScore procedure...\n";
} else {
    die("Error creating updateScore procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$updateStock = "DROP PROCEDURE IF EXISTS `updateStock`;
CREATE PROCEDURE `updateStock` (IN `stock` INT, IN `serial` INT UNSIGNED)
BEGIN
UPDATE `Inventory`
SET InStock = InStock + stock
WHERE GSerial = serial;
END;";

if (mysqli_multi_query($con, $updateStock)) {
    echo "Successfully created updateStock procedure...\n";
} else {
    die("Error creating updateStock procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

$listCustomersOfGame = "DROP PROCEDURE IF EXISTS `customersOfGame`;
CREATE PROCEDURE `customersOfGame`(IN `gameTitle` VARCHAR(40))
BEGIN
SELECT `FirstName`, `MidInitial`, `LastName`, `Age`, `CustomerID`, `Gender`
FROM (Games INNER JOIN Orders using(GSerial))
INNER JOIN Customers using(CustomerID)
WHERE Title = gameTitle;
END;";

if (mysqli_multi_query($con, $listCustomersOfGame)) {
    echo "Successfully created customersOfGame procedure...\n";
} else {
    die("Error creating customersOfGame procedure (" . mysqli_errno($con) . "): " . mysqli_error($con) . "\n");
}

/* Get rid of results of query */
while (mysqli_more_results($con) && mysqli_next_result($con));

/*
$q2 = "Insert into Games(Title, Price, updatedAt) Values('Grand Theft Auto V','54.50','". date("Y-m-d H:i:s") ."')";
$q1 = "Insert into Inventory(GSerial, InStock, NumSold) Values('1','10','5')";
$q4 = "Insert into Games(Title, Price, updatedAt) Values('Super Mario','49.50','". date("Y-m-d H:i:s") ."')";
$q3 = "Insert into Inventory Values('111','10','5')";
$q6 = "Insert into Games(Title, Price, updatedAt) Values('Pokemon','112','49.50','2')";
$q5 = "Insert into Inventory Values('112','10','5')";
$q8 = "Insert into Games(Title, Price, updatedAt) Values('Final Fantasy','113','49.50','2')";
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
mysqli_close($con);
?>