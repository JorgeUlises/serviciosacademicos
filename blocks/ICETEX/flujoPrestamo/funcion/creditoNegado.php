<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */







	//echo '<br><div style = "font-style:italic;text-align: center;"><b>';
	//echo $this->lenguaje->getCadena("creditoNegado");
	//echo "</b><br>";
	//echo  '</div>';
    $this->miMensaje->addMensaje("8","creditoNegado","error");
    echo $this->miMensaje->getLastMensaje();
	exit;




return true;






