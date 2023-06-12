<?php
$color = array('white', 'green', 'red');
print_r($color);
asort($color);
echo '<br>Array<br>File: '. __FILE__.'<br>Line: '.__LINE__.'<br><pre>';print_r($color);echo '</pre>'; die();

?>