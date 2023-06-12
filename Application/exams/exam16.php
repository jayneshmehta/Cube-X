<?php
 require_once("./Calculator.php");   

$obj = new Calculator(12,6);
$add = $obj->add();
echo "Add = ".$add."<br>";
$sub = $obj->sub();
echo "Sub = ".$sub."<br>";
$div = $obj->div();
echo "Div = ".$div."<br>";
$multi = $obj->multi();
echo "multi = ".$multi."<br>";

?>