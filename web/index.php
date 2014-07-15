<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Paris');

define('WEBROOT', dirname(__FILE__)); 
define('ROOT', dirname(WEBROOT)); 
define('DS', DIRECTORY_SEPARATOR);

define('CORE', ROOT.DS.'lib');
define('APP', ROOT.DS.'app');

define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME']))); 

require 'TM4B/Autoloader.php';
require CORE.DS.'includes.php'; 
new Dispatcher(); 

?>