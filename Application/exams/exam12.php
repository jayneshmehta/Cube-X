<?php

$str = 'The brown fox';
$str2 ='quick';
$output = substr_replace($str, $str2.' ', 4, 0);
echo $output;
?>