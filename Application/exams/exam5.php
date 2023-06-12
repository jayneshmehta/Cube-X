<?php
$date1 = date_create("1981-11-04");
$date2 = date_create("1991-10-23");

$diff = date_diff($date1,$date2);
echo $diff->format("%Y Years , %M month , %d days");
?>