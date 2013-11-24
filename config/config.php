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

$conf['BASEURL'] = 'http://localhost/git/';
$conf['DOC_ROOT'] = '/opt/lampp/htdocs/git/';
$conf['db_host'] = 'localhost';
$conf['db_user'] = 'root';
$conf['db_pass'] = 'password';
$conf['db_name'] = 'GameStore';
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