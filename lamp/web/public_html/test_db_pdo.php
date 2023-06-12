<?php

$DBuser = 'root';
$DBpass = 'deep70';
$pdo = null;

try{
    $database = 'mysql:host=database:3306';
    $pdo = new PDO($database, $DBuser, $DBpass);
    echo "Success: A proper connection to MySQL was made!";    
} catch(PDOException $e) {
    echo "Error: Unable to connect to MySQL. Error:\n $e";
}

$pdo = null;