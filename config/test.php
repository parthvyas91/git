<?php

require_once('config.php');

$con=mysqli_connect($conf['db_host'],$conf['db_user'],$conf['db_pass'],$conf['db_name']);

// Check connection
if (mysqli_connect_errno($con))
{
    die("Failed to connect to MySQL and database GameStore: " . mysqli_connect_error() . "\n");
}

?>