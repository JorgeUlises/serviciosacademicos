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



$cadena_sql = $this->sql->cadena_sql("consultarPagoReferenciaMatricula",$_REQUEST['valorConsulta']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoConsulta");
	echo "</b></p></div>";
	exit;
}


switch($registros[0][1]){
	case 'S':
		
		break;
	case 'N':
		
		
		$this->estado = 8;
		$this->actualizarEstadoFlujo();
		echo json_encode(true);
		exit;
		break;
	default:
		break;
}





