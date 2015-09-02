<?php


// Connects to the XE service (i.e. database) on the "localhost" machine
$conn = oci_connect('SGA', 'mangobiche', 'localhost/XE');
if (!$conn) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, 'SELECT 1 FROM dual');
oci_execute($stid);
print_r($stid);