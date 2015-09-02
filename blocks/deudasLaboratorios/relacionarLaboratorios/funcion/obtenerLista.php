<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

if(isset($_REQUEST['modulo'])){
	switch($_REQUEST['modulo']){
	case "80":
		$conexion="soporteoas";
		break;	
	default: 
		$conexion="estructura";
	break;
	}
}else exit;


$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );
$rutaURL .="/blocks/".$_REQUEST['bloqueGrupo']."/".$_REQUEST['bloqueNombre'];

$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}

//Selecciona el tipo de consulta
$cadena_sqlD = $this->sql->cadena_sql("consultarUsuarioLaboratorio", "");
$registrosD = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");
if($registrosD==null){
		echo utf8_encode($this->lenguaje->getCadena("errorConsultaRelacion"));
		exit;
}

$titulo =$this->lenguaje->getCadena("resultado");

//Mostrar Tabla

//Inicio Tabla
$cadena = '<br><table class="tablaGenerica" id="tablaEdicion" style="margin: 0 auto;"><tr>';

//encabezados
foreach ($registrosD[0] as  $att => $val){
	$string = str_replace(' ', '', $att);
	$string = preg_replace('/\s+/', ' ', $string);
	$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
	if(!is_numeric($att)&&$att!='id') $cadena.='<td id="'.$string.'">'.$att.'</td>';
}
$cadena .= "<td></td></tr>";

//Valores Tabla

foreach ($registrosD as $valor){
	$cadena .= "<tr>";
	foreach ($valor as  $att => $val){
		$string = str_replace(' ', '', $att);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
		if(!is_numeric($att)&&$att!='id')
			$cadena.='<td headers="'.$string.'">'.$val.'</td>';
		
	}
	$cadena .= "</td>";
	
	//Edicion
	$cadena .= "<td>";
	$cadena	.='<div class="edicionMenu" id="edicionMenu">';
	$cadena .= '<div class="listaAccion"><a onclick="editarElemento(\''.$valor['id'].')" style="height:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/edit.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaEditar")).'"></a></div>';
	$cadena .='</div>';
	$cadena .= "</td></tr>";
}



//fin tabla
$cadena .= "</table><br>";

//Boton agregar
$cadena .= '<div style="text-align: center;"><input type="button" name="agregarRelacion" id="agregarRelacion" onclick="agregarRelacion();" ';
$cadena .= 'value="'.$this->lenguaje->getCadena("agregar").'"';
$cadena .= '></input></div>';


echo $cadena;

exit;

