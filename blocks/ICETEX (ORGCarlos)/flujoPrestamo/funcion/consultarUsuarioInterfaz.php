<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
// Buscar proveedores


if($_REQUEST["modulo"]==51||$_REQUEST["modulo"]==52){
	$_REQUEST['valorConsulta'] = $_REQUEST['usuario'];
	$this->workflow();
	exit;
}

echo '<div id="consulta" style="text-align: center;">';
echo '<form name="formulario" id="formulario">';
echo "<b>".$this->lenguaje->getCadena("seleccionar")."</b>:";


echo '<input type="text" width="80px" id="valorConsulta" name="valorConsulta" class="ui-widget ui-widget-content ui-corner-all validate[required, custom[onlyLetterNumber]]" title="'.utf8_encode($this->lenguaje->getCadena("ingreseValor")).'"></input>';
echo "</form>";
echo '<input type="button" width="30px" id="consultarUsuario" ';

if(isset($_REQUEST["soloConsulta"])) echo ' onclick="consultarUsuarioNoEdicion();"';
else echo '		onclick="consultarUsuario();" ';

echo '		value="'.utf8_encode($this->lenguaje->getCadena("consultarBoton")).'"></input>';
echo "</div>";
echo '<div id="consultaResultado">';
echo '<div id="resultadoUsuario">';
echo "</div>";
echo '<div id="resultadoCredito">';
echo "</div>";
echo "</div>";
echo '<div id="listado">';
echo "</div>";


