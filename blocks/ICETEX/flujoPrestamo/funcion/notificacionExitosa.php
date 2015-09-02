<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */


//echo '<br><br><div style="text-align: center;font-style: italic;color:#FF0000;">';
//echo "<b>El estudiante ha sido notificado exitosamente<br><br>";

//echo "</div><br>";

$this->miMensaje->addMensaje("19","notificacionExitosa","information");
echo $this->miMensaje->getLastMensaje();





