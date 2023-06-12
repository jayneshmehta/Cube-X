<?php
$array1 = array(array(77, 87), array(23, 45));
$array2 = array("w3resource", "com");

foreach ($array2 as $key => $value) {
    array_unshift($array1[$key], $array2[$key]);
}
print_r($array1);


?>