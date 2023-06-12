<?php
    session_start();
echo "this is page one ..<br>";
setcookie("username","Jaynesh Mehta",time() + 120,"/");
header("Location:page2.php");
?>