<?php
$str = "http://www.example.com/5478631";
echo "result = ".str_replace("/","",strrchr($str,"/"));
?>