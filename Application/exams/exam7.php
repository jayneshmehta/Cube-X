<?php
$temp = array( 78, 60, 62, 68, 71, 68, 73, 85, 66, 64, 76, 63, 75, 76, 73, 68, 62, 73, 72, 65, 74, 62, 62, 65, 64, 68, 73, 75, 79, 73);
$avg = 0 ;
$sum = 0; 
foreach ($temp as $key => $value) {
   $sum = $sum+$value;
}
$avg = $sum/count($temp);
echo "Average temp = ".$avg ."<br>";
$higest = [];
$lowest = [];
$temp2 =array_unique($temp);
sort($temp2);
$i=1; 
while($i <= 5){
array_push($lowest,$temp2[$i]);
array_push($higest,$temp2[count($temp2)-$i]);
$i++;
}
echo "heigest : ";
echo '<br><br>File: '. __FILE__.'<br>Line: '.__LINE__.'<br><pre>';print_r($higest);echo '</pre>'; 
echo "Lowest : ";
echo '<br><br>File: '. __FILE__.'<br>Line: '.__LINE__.'<br><pre>';print_r($lowest);echo '</pre>'; 
?>