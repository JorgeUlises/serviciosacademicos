<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */


//echo '<br><br><div style="text-align: center;font-style: italic;color:blue;">';
//echo "<br>Se esta en espera de Verificacion de datos <br>";
//echo "por parte de bienestar para continuar con el proceso";
//echo "</div><br>";

$this->miMensaje->addMensaje("21","notificacionVerificar","information");
echo $this->miMensaje->getLastMensaje();





