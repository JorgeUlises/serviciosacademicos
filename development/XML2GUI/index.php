<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es"><head>
<title>Convertidor XML a Interfaz de usuario GUI</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="icon" href="http://localhost:8087/sara/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/general.css">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/estiloCuadrosMensaje.css">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/estiloTexto.css">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/estiloFormulario.css">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/validationEngine.css">
<link rel="stylesheet" type="text/css" href="XML2GUI_files/XML2GUI.css">
<script type="text/javascript" src="XML2GUI_files/funciones.js"></script><script type="text/javascript" src="XML2GUI_files/jquery_003.js"></script>
<script type="text/javascript" src="XML2GUI_files/XML2GUI.js">
</script>

<script type="text/javascript" src="XML2GUI_files/jquery.js">
</script>

<script type="text/javascript" src="XML2GUI_files/jquery_002.js">
</script>

<script type="text/javascript">

	function postTexto(){
		//valida el formulario usando validation engine antes de enviar
		if($("#restUI").validationEngine('validate')!=false){
			var xmlText=document.getElementById('xmlBody').value;
			postXML2GUI(xmlText);
			}
	}

	function enviarFormularioAjax() {
		//valida el formulario usando validation engine antes de enviar
		if($("#restUI").validationEngine('validate')!=false){
			getXML();
			}
	}

	function getXML(){
		$.ajax({
            url: document.URL.substr(0,document.URL.lastIndexOf('/'))+$('#valor').val(),
            type:"GET",
            dataType: "xml",
            success: loadXML,
            error:function(err){console.log(err);}
        });
		}

	function loadXML(retorna){
        	var xmlText = new XMLSerializer().serializeToString(retorna);
        	var parentDiv = document.getElementById('xmlBody');
        	parentDiv.style.height="300px";
        	parentDiv.value=xmlText.trim();
        	parentDiv.innerHTML=xmlText.trim();
        	postXML2GUI(xmlText.trim());		
		}
	
	function postXML2GUI(xmlText){
		$.ajax({
            url: document.URL.substr(0,document.URL.lastIndexOf('/'))+"/XML2GUI.php",
            type: 'POST',
            contentType: "text/xml",
            dataType: "text",
            data:xmlText,
            error:function(err){console.log(err);},
            success: function(retorna){
            	var guiDiv = document.getElementById('gui');
            	guiDiv.innerHTML=retorna;
            	$("#forma").fadeOut();
            	$("#xmlDoc").fadeOut();
            	$("#gui").fadeIn();
	                       	
	       }
        });
		}

</script><script type="text/javascript">
$(document).ready(function(){

// Asociar el widget de validación al formulario
$("#restUI").validationEngine({promptPosition : "centerRight", scroll: false});

$(".expandido").click(function(){
	$(this).next().fadeToggle();
});

$(".contraido").click(function(){
	$(this).next().fadeToggle();
});


document.getElementById('urlPost').innerHTML=document.URL.substr(0,document.URL.lastIndexOf('/'))+"/index.php";


});
</script>
</head>
<body>
<div id="marcoGeneral">
<div id="seccionAmplia">
<div>
<span>URL POST</span></div>
<div><span id="urlPost"></span></div><br>
<div class="expandido">
<span class="textoEnorme texto_negrita">Enviar XML y recibir GUI</span></div>
<div class="divisionBloques" id="forma">
<form id="restUI" enctype="multipart/form-data" method="post" name="restUI">
<div class="campoCuadroTexto">
<div style="float:left; width:120px"><label for="valor">URL</label>
</div>
<input class="cuadroTexto  validate[required] " title="Ingrese una URL que contenga un XML" name="valor" id="valor" value="/test.xml" size="80" maxlength="100" tabindex="1" type="text">
</div>
<div class="marcoBotones" id="botones">
<div class="marcoBotones">
<button value="POST" id="peticionAjaxA" tabindex="2" type="button" onclick=" enviarFormularioAjax();" "="">POST</button>
<input name="peticionAjax" id="peticionAjax" value="false" type="hidden">
</div>

</div>
</form>

</div>
<br><br><br><div class="expandido">
<span class="textoEnorme texto_negrita">XML</span></div>
<div style="display:none;" id="xmlDoc">
<form id="textoXML" enctype="multipart/form-data" method="post" name="textoXML">
<textarea id="xmlBody" style="width:100%;" class="xmlTexto validate[required]" tabindex="3" rows="2" cols="50" name="xmlBody"></textarea><div class="marcoBotones" id="botones">
<div class="marcoBotones">
<button value="POST" id="peticionAjaxA" tabindex="3" type="button" onclick=" postTexto();" "="">POST</button>
<input name="peticionAjax" id="peticionAjax" value="false" type="hidden">
</div>

</div>
</form>

</div>
<br><br><div class="expandido">
<span class="textoEnorme texto_negrita">Formulario</span></div>
<div class="divisionBloques" style="display:none;" id="gui">

</div>
</div>
</div>


</body></html>