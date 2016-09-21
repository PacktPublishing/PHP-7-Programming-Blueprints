<?php
require('spl_autoloader_function.php');

$db = new \DB; //loads our DB from src folder, using the spl_autoload_functionabove.
$dbConnectionDetails = $db->getConfig('dbconfig.php');
$dbContainer = getDB($dbConnectionDetails);

?>
