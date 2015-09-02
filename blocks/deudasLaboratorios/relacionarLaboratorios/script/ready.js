
// Asociar el widget de validación al formulario
$("#formulario").validationEngine({promptPosition : "centerRight", scroll: false});
$( "[title]" ).tooltip();


var read; 
if(!document.getElementById("leido")){
	
	//machetazo el ready se ejecuta dos veces
	read = document.createElement("div");
	read.id="leido";
	document.body.appendChild(read);
	
	//inicia los tabs
	$( "#tabs" ).tabs({ collapsible: true });
	
	//Obtiene Interfaz de consulta
	obtenerInterfaz();
	
	$( "#ui-id-2" ).click(function() {
		var div = document.getElementById("edicion");
		div.innerHTML ="";
		obtenerListaElementos();
	});
	
	$( "#ui-id-1" ).click(function() {
		var div = document.getElementById("consultas");
		div.innerHTML ="";
		obtenerInterfaz();
	});
	
	
	

	
	}

