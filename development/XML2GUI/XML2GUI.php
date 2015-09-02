<?php
set_include_path(__DIR__."/../../");
include_once("core/builder/XML2GUI.class.php");

$xml = file_get_contents("php://input");
$miConvertidor=new XML2GUI();
$miConvertidor->convertir($xml);
?> 


