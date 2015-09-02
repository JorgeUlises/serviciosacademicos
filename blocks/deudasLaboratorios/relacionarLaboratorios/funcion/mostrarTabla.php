<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

echo '<br><table style="margin: 0 auto;">';

//echo '<tr>';
echo '<thead><tr><th colspan="2"><b>';
echo utf8_encode($this->titulo);
echo '</b></th></tr></thead>';
//echo '</tr>';

foreach ($this->reg[0] as $a=>$b)
//&&$b!=end($this->reg[0])
if(!is_numeric($a)){
	echo '<tr>';
	echo '<td><b>';
	echo utf8_encode($a);
	echo '</b></td>';
	echo '<td>';
	echo $b;
	echo '</td>';
	echo '</tr>';
}



echo '<br></table><br>';
$_REQUEST['valorConsulta'] = $this->reg[0][1];
$_REQUEST['tipoDeudor'] = end($this->reg[0]);
$this->nuevoDeuda();

exit;