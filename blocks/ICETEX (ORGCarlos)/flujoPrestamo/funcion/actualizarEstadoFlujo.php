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

//Asigna Variables
$parametro = array();
$parametro['codigo'] = $_REQUEST['valorConsulta'];
$parametro['estado'] = $this->estado;


$cadena_sqlD = $this->sql->cadena_sql("consultarEstadoFlujo",$_REQUEST['valorConsulta']);
$registrosD = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");



if($registrosD!=false){
	//Actualiza el estado del flujo
	$cadena_sql = $this->sql->cadena_sql("actualizarFlujo",$parametro);
	
	$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
	if($registros!=false){
		//echo "error Actualizando Flujo";
		exit;
	}
}else{
	//crea registro en el flujo
	$cadena_sql = $this->sql->cadena_sql("creaFlujo",$parametro);
	$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
	if($registros!=false){
	
		exit;
	}
}




return true;




