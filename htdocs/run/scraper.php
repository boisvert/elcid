<?php

$binpage = fopen("http://codepad.viper-7.com","r");
$contents = stream_get_contents($binpage);
fclose($binpage);

$dom = new DOMDocument();
@$dom->loadHTML($contents);
$x = new DOMXPath($dom); 

foreach($x->query("//html/body/div/div/div/form/fieldset/input") as $node) {
   $code = $node->getAttribute("value");
} 
echo $code;

?>