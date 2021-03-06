<?php

class LenguajerelacionarLaboratorios{

	private $idioma;
	
	private $miConfigurador;
	
	private $nombreBloque;
	
	function __construct($ruta = "")
	{
	
		$this->miConfigurador=Configurador::singleton();
		
		$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");
		$this->nombreBloque=$esteBloque["nombre"];
		
		if($ruta=="")
			$this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
		else $this->ruta = $ruta ;
		
		if($this->miConfigurador->getVariableConfiguracion("idioma")){
			$idioma=$this->miConfigurador->getVariableConfiguracion("idioma");
		}else{
			$idioma="es_es";
		}
		include($this->ruta."/locale/".$idioma."/Mensaje.php");
	}
	
	
	
	public function getCadena($opcion=""){
	
		$opcion=trim($opcion);
		if(isset($this->idioma[$opcion])){
			return $this->idioma[$opcion];
		}else{
			return $this->idioma["noDefinido"];
		}
		
	}
}


?>
