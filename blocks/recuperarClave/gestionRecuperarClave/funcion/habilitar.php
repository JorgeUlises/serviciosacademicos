<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

$conexion="soporteoas";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}



//

$codigo = $_REQUEST['valorConsulta'];
$usuario =  $_REQUEST['usuario'];
//Valida que le estudiante exista
$cadena_sql = $this->sql->cadena_sql("conteoEstudiante",$codigo);
 
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

if($registros==false||$registros[0][0]<1){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}


//Update Geclaves
$cadena_sqlUpdate = $this->sql->cadena_sql("updateGeclaves",$codigo);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sqlUpdate);

if($registros!=false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorUpadate");
	echo "</b></p></div>";
	exit;
}

//Insert Registro
$registrar =  array();
$registrar['estudiante'] =  $codigo;
$registrar['usuario'] = $usuario;
$cadena_sqlInsert = $this->sql->cadena_sql("agregarRegistro",$registrar); 
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sqlInsert);

if($registros!=false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorRegistro");
	echo "</b></p></div>";
	exit;
}

echo '<div style="text-align: center"><p><b>';
echo $this->lenguaje->getCadena("registroExitoso");
echo "</b></p></div>";
exit;





exit;


