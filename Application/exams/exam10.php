<?php
require_once('Sort.php');

$array1 = array(11, -2, 4, 35, 0, 8, -9); 
 $obj = new Sort();
 $arr = $obj->sortarr($array1);
print_r($arr);
?>