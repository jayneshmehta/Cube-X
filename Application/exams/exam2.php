<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table,td,tr{
        border: 2px solid black;
        border-collapse: collapse;
    }
</style>
<body>
<table >
<?php
echo "<b>Question 2 : </b><br><br>";
   for($i=1; $i<=6;$i++){
    echo "<tr>";
    for($j=1; $j<=5;$j++){
        echo "<td style='padding:3px;'>$i * $j = ". $i*$j ."</td> ";
    }
    echo "</tr>";
   } 
?>    
</table>
</body>
</html>
