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

if($_REQUEST["modulo"]==51||$_REQUEST["modulo"]==52){
	$_REQUEST['valorConsulta'] = $_REQUEST['usuario'];
	$this->workflow();
	exit;
}

echo '<div id="consulta" style="text-align: center;">';

//Inicio Formulario
echo '<form name="formulario" id="formulario">';

echo '<table class="noHover">';
echo '<tr class="noHover">';
echo '<td>';
echo "<b>".utf8_encode($this->lenguaje->getCadena("seleccionar"))."</b>:";
echo '</td>';
echo '<td>';
echo '<select id ="opcionConsulta" name="opcionConsulta">';
echo '<option value="cedula">'.utf8_encode($this->lenguaje->getCadena("seleccionarCedula")).'</option>';
echo '<option value="codigo">'.utf8_encode($this->lenguaje->getCadena("seleccionarCodigo")).'</option>';
echo '</select>';
echo '</td>';
echo '<td>';
echo '<input type="text" width="80px" id="valorConsulta" name="valorConsulta" class="ui-widget ui-widget-content ui-corner-all validate[required, custom[onlyLetterNumber]]" title="'.utf8_encode($this->lenguaje->getCadena("ingreseValor")).'"></input>';
echo '</td>';
echo "</tr>";
echo '<tr class="noHover">';



echo '<td>';
echo "<br><b>".utf8_encode($this->lenguaje->getCadena("periodo"))."</b>:";
echo '</td>';
echo '<td>';
//consulta Períodos Actual y anterior
$cadena_sqlL = $this->sql->cadena_sql("listadoPeriodos",'');
$registrosL = $esteRecursoDB->ejecutarAcceso($cadena_sqlL,"busqueda");
//string select periodos
$strListado ='<select name="periodo" id="periodo">';
        foreach ($registrosL as $el){
                $strListado .='<option value="'.$el[0].'">';
                $strListado .=$el[0];
                $strListado .='</option>';
        }
$strListado .='</select>';

echo ''.$strListado.'';
echo '</td>';

echo '<td>';
echo '</td>';

echo "</tr>";

echo '</table>';

//Fin formulario
echo "</form>";
echo '<input type="button" width="30px" id="consultarUsuario" ';

if(isset($_REQUEST["soloConsulta"])&&isset($_REQUEST["periodo"])) echo ' onclick="consultarUsuarioNoEdicion();"';
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


