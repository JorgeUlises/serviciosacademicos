<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */



$conexion="mantis";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}


//Obtener uri WSDl









echo '<div id="consulta" style="text-align: center;">';
echo '<form name="formulario" id="formulario">';




echo '<table style="margin: 0px auto;text-align: right;">';


echo '<tr><td>';
echo "<b>".utf8_encode($this->lenguaje->getCadena("codigo"))."</b>";
echo '</td><td>';
echo '<input type="text" width="80px" id="valorConsulta" name="valorConsulta" class="ui-widget ui-widget-content ui-corner-all validate[required, custom[number]]" title="Ingrese un valor para la consulta">' ;
echo '</td></tr>';


echo '</table>';


echo '<div style="display: inline-block;">';
echo '<input type="button" width="30px" id="consultarUsuario" onclick="enviarGestion()" value="'.utf8_encode($this->lenguaje->getCadena("habilitar")).'"></input>';
echo '</div>';
echo '</div>';

echo "</div>";
echo '<div id="resultado">';
echo '<div id="resultadoUsuario">';
echo "</div>";
echo '<div id="resultadoDeudas">';
echo "</div>";
echo "</div>";
echo '<div id="listado">';
echo "</div>";



