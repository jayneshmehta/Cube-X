<?php
require_once "../../lib/siteConstant.php";
    $date = date_create("2012-09-12");
   echo "Output : " .date_format($date,"d-m-Y") ."<br>"; 
?>