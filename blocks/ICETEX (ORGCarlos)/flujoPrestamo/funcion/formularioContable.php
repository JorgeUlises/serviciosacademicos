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


echo "<br>";

echo '<div id="resolucion" style="text-align: left;padding: 10px;">';
echo '<div style="text-align: center;padding: 10px;">';
echo "<h3>".$this->lenguaje->getCadena("tituloformularioContable")."</h3>";
echo '</div>';



echo '<form method="post" enctype="multipart/form-data" name="formularioContable" id="formularioContable">';



echo "<div style =\"font-style:italic;text-align: center;\">".$this->lenguaje->getCadena("notaExcelMarca")."</div>";
echo "<p style=\"text-align: center;padding: 5px;\"><b>".$this->lenguaje->getCadena("documentoExcel")."</b>:";
echo '<input type="file" width="80px" id="excelMarca" name="excelMarca" class="ui-widget ui-widget-content ui-corner-all validate[required]" title="'.utf8_encode($this->lenguaje->getCadena("ingreseValorX")).'"></input>';
echo "</p>";


echo '<div style="text-align: center;padding: 10px;">';
echo '<input type="button" onclick="enviarMarca();" width="30px" id="consultarUsuario" value= "'.$this->lenguaje->getCadena("enviarMarca").'">';
echo '</div>';
echo "</form>";

echo "</div>";
echo "<div id='rconsultas'>";
echo "</div>";


