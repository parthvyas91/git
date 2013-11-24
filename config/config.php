<?php
/**
 * config.php
 *
 * @author SJSU Students
 *
 */
define ("BASEURL", 'http://localhost/git/');
define ("SITENAME", "Game Store");
define ("USERNAME", 'root');
define ("PASSWORD", "");
/**
 * This function update $data when change is made
 * return $data
 */
function updateAllEntries()
{
    global $data;
    $data = getAllEntries();
}

?>

