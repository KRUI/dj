<?php
$request_url = "http://www.google.com/ig/api?weather=Iowa%20City%20IA";
$xml = simplexml_load_file($request_url) or die("feed not loading");
$current = $xml->weather->current_conditions->condition[data];
echo "<pre>";
print_r($xml);
echo "</pre>";
?>
