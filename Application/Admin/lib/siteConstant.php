<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ERROR);

$CurrentProtocol = "https://";
$directory = '/';
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
    $CurrentProtocol = "http://";
}
define('SITE_PROTOCOL', $CurrentProtocol);
if (strpos($_SERVER['HTTP_HOST'], "opsusers") !== FALSE || strpos($_SERVER['HTTP_HOST'], "training2023") !== FALSE) {
    $directory .= 'jaynesh/'; // change with your name (all lower case, and do not remove /)
}
define('SITE_DIRECTORY', $directory);
define('SITE_URL', SITE_PROTOCOL . $_SERVER['HTTP_HOST'] . SITE_DIRECTORY);
