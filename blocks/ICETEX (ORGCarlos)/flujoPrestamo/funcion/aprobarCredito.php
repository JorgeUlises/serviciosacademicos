<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */


$conexion="icetex";

$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}

//Revisa si existen recibos creados en el año y periodo en curso
$cadena_sql = $this->sql->cadena_sql("consultarRecibosCreados",$_REQUEST['valorConsulta']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoRecibo");
	echo "</b></p></div>";
	exit;
}

//Revisa si algun recibo se ha pagado
$validaPago = false;
foreach ($registros as $reg){
	if($reg[1]=='S') $validaPago = true;
}

if($validaPago==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoReciboPagado");
	echo "</b></p></div>";
	exit;
	
}

$this->estado = 3;

//Actualiza Estado del flujo
$this->actualizarEstadoFlujo();

echo json_encode(true);
/*
//Revisa si el credito esta aprobado
$cadena_sql = $this->sql->cadena_sql("consultarCreditoAprobadoReintegro",$_REQUEST['valorConsulta']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<br><div style = "font-style:italic;text-align: center;"><b>';
	echo $this->lenguaje->getCadena("esperaAprobacionCredito");
	echo "</b><br>";
	echo  '<input onclick="aprobarCredito('.$_REQUEST['valorConsulta'].')" type="button" value="'.$this->lenguaje->getCadena("botonAprobarCredito").'"></input>';
	echo  '<input onclick="cancelarCredito('.$_REQUEST['valorConsulta'].')" type="button" value="'.$this->lenguaje->getCadena("botonCancelarCredito").'"></input>';
	echo '</div>';
	exit;
}
*/
//$this->formularioResolucion();

return true;






