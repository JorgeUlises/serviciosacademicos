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

//CLASE MAPEA RESPUESTA
class Transaccion {
	public $banco;
	public $cus;
	public $descripcion;
	public $estado;
	public $fechaFin;
	public $fechaInicio;
	public $identificacionUsuario;
	public $referencia;
	public $tipoUsuario;
	public $valor;
	public $sucursal;
	public $codigoUsuario;
}


//Obtiene array de codigos 

$this->procesarExcelMarcas();

//asigna valores

$parametros = array();


$listaError = array();
$listaExito = array();
$listaMensajes=array();
$errorConsulta = false;
$errorUpdate = false;
$errorPago = false;	 
$errorResolucion = false;	 
//procesa el listado registra la resolucion para cada uno del listado
foreach ($this->listadoCodigos as $lista){
	
		
		if(isset($lista[0])) {
			$parametros["codigo"] = $lista[0];
			$_REQUEST["codigo"] = $lista[0];
		}		else{
			$parametros["codigo"] =0;
			$_REQUEST["codigo"] = 0;
		}
		
		
		//Es necesario tomar de la$registrosRefS  tabla parametrica los valores de sistematizacion y otros
        $parametros['anio'] = substr($_REQUEST['periodo'], 0, 4);
        $parametros['per'] = substr($_REQUEST['periodo'], 5, 1);
		$cadena_sqlRefS = $this->sql->cadena_sql("consultarPagoReferenciaMatricula",$parametros);
		$registrosRefS = $esteRecursoDB->ejecutarAcceso($cadena_sqlRefS,"busqueda");
		
		
		
		if($registrosRefS==false){
			
			$errorConsulta = true;
			
		}
		
		$valorPagado = 0;
		
		$secuenciasXCodigo = array();
		
		
		$secuencias =  array();
		if(count($registrosRefS)>1){
			
			//DIFERIDOS
			
			
			foreach ($registrosRefS as $reg){
			     
				if($reg['EMA_PAGO'] == 'S'){
					$valorPagado += $reg['EMA_VALOR'];
				}else{
					$secuencias[] = array($reg['EMA_SECUENCIA'],$reg['EMA_VALOR']);
				}
			}
			
			
			
		}else{
		
			if($registrosRefS[0][1]==='S'){
				
				$_REQUEST["valorConsulta"] = $_REQUEST["codigo"];
				$this->estado = 10;
				$this->actualizarEstadoFlujo();
				$errorPago = true;
				$valorPagado += $registrosRefS[0]['EMA_VALOR'];
				
				
			}else{
				$secuencias[] = array($registrosRefS[0]['EMA_SECUENCIA'],$registrosRefS[0]['EMA_VALOR']);
			}
			
		}
		
                //Revisa si existe resolucion registrada para el recibo
                $cadena_sql = $this->sql->cadena_sql("consultarResolucionCredito",$parametros);
                
                $registroResolucion = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
                $validaResolucion = false;
                if(is_array($registroResolucion)){
                    foreach ($registroResolucion as $reg){
                            if(is_numeric($reg[1]) && $reg[1]>0) $validaResolucion = true;
                    }
                }
                
                
                if($validaResolucion!=true){
				    $errorResolucion = true;
			       }
			       
		if($errorConsulta == false&&$errorPago==false && $errorResolucion==false){
			
			//consultar cedula estudiante
			$cadena_sql = $this->sql->cadena_sql("consultarIdentificacionCodigo",$parametros);
			$registroIdentificacion = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
			
			
			$parametros['valorPago'] =  $registroResolucion[0]['CRE_VALOR_INDIVIDUAL'];
			
			$parametros['dia']= date('d');
			$parametros['mes']= date('m');
			$parametros['ano']= date('y');
			
			$parametros["secuencia"] = $registrosRefS[0]['EMA_SECUENCIA'];
			
			//PARAMETROS CREATE
			$banco =  (string) "ICETEX";
			$cus = (int)  0;
			$descripcion = (string) "1-MATRICULA";
			$estado = "OK";
			$fechaFin = date('Y-m-d H:i:s');
			$fechaInicio = date('Y-m-d H:i:s');;
			$cedulaEstudiante = (int) $registroIdentificacion[0]['EST_NRO_IDEN'];
			//$referencia = (int) $parametros['secuencia'];
			$tipoUsuario = 0;
			$valorPago = $parametros['valorPago'];
			$sucursal = 0;
			$codigoEstudiante = (int) $parametros['codigo'];
			
			
			
			foreach ($secuencias as $referencia){
				
				
				$aPagar = 0;
				
				if($parametros['valorPago']>=$referencia[1]){
					$parametros['valorPago'] = $parametros['valorPago'] - $referencia[1];
					$aPagar = $referencia[1];
				}else{
					$aPagar = $parametros['valorPago'];
				}
				
				$objetoTranasaccion =  new Transaccion ();
					
				$objetoTranasaccion->banco = $banco;
				$objetoTranasaccion->cus = $cus;
				$objetoTranasaccion->descripcion = $descripcion;
				$objetoTranasaccion->estado = $estado;
				$objetoTranasaccion->fechaFin = $fechaFin;
				$objetoTranasaccion->fechaInicio = $fechaInicio;
				$objetoTranasaccion->identificacionUsuario = $cedulaEstudiante;
				$objetoTranasaccion->referencia = $referencia[0];
				$objetoTranasaccion->tipoUsuario = $tipoUsuario;
				$objetoTranasaccion->valor = $aPagar;//ES EL VALOR DEL RECIBO
				$objetoTranasaccion->sucursal = $sucursal;
				$objetoTranasaccion->codigoUsuario = $codigoEstudiante;
			
				$arrayss = array();
				
				$arrayss['objTransaccion'] = $objetoTranasaccion;
				
				//$wsdl = 'https://portalws.udistrital.edu.co/reportePagoUD/ws/TransaccionWS.php?wsdl';
				$wsdl =  $this->miConfigurador->getVariableConfiguracion ( "wsdlPagoRecibo" );
				
				///Crear cliente SOAP
				$soap_options = array(
						'trace'       => 1,     // traces let us look at the actual SOAP messages later
						'exceptions'  => 1,
						//'proxy_host'  => 'proxy.udistrital.edu.co',
						//'proxy_port'  => '3128'
				);
				
				try{
					$client = new SoapClient($wsdl ,$soap_options);
					
						$soapVariable = new SoapVar($arrayss, SOAP_ENC_OBJECT);
						
						$transaccion = $client->creaTransaccion($soapVariable);
						
						//var_dump($soapVariable,$transaccion );
						
						if(strtolower($transaccion->return)!="ok"){
							$errorUpdate = true;
						}else{
							$secuenciasXCodigo[] = $objetoTranasaccion->referencia;
						}
	
				} catch (SoapFault  $f)	{
					$errorUpdate = true;
				
				}
				
			}
			
			
		}
		
		if($errorUpdate==false&&$errorConsulta == false&&$errorResolucion==false){
			array_push($listaExito,$parametros["codigo"]);
		}else{
			
			$arrayp = array();
			if($errorUpdate!=false) array_push($arrayp,array($parametros["codigo"],'Actualizar pago recibo'));
			if($errorConsulta!=false) array_push($arrayp,array($parametros["codigo"],'No Consulta Recibo'));
			
			if($errorResolucion!=false) array_push($arrayp,array($parametros["codigo"],'No registra resolucion'));
				
				
			array_push($listaError,$arrayp );
				
		}
		
		$arraym =  array();
		
		if($valorPagado>0) $errorPago = true;
		
		if($errorPago!=false){ 
			$recibos = count($secuenciasXCodigo)>0?'<br> Nro de recibos pagados: '.implode($secuenciasXCodigo,","):""; 
			array_push($arraym,array($parametros["codigo"],'Matrícula Paga - valor del pago '.$valorPagado.$recibos));
			array_push($listaMensajes,$arraym );
		}
		
		$errorConsulta = false;
		$errorUpdate = false;
		$errorPago = false;
		$errorResolucion = false;


}

//Exitoso
if(count($listaExito)>0){
	echo '<div><p><b>';
	echo $this->lenguaje->getCadena("exitoRegistroResolucion")." <br>";
	echo '<div style="text-align: center;">';
	foreach($listaExito as $li){
		echo $li."<br>";
	}
	echo "</b></p></div></div><br>";
	
	$_REQUEST["valorConsulta"] = $li;
	$_REQUEST["codigo"] =  $li;
	$this->estado = 10;
		
	$this->actualizarEstadoFlujo();
	$this->registroLog('MARCA RECIBO '.implode($secuenciasXCodigo,",").$li);
	
}


//Mensaje
if(count($listaMensajes)>0){
	echo '<div ><p><b>';
	echo $this->lenguaje->getCadena("mensajeRegistroResolucion")." <br>";
	echo '<div style="text-align: center">';
	foreach($listaMensajes as $li){
		echo $li[0][0]." - ".$li[0][1]."<br>";
	}
	echo "</b></p></div></div><br><br>";
	
	$this->estado = 10;
	$this->actualizarEstadoFlujo();
	
}
			
//Error
if(count($listaError)>0){
	echo '<div ><p><b>';
	echo $this->lenguaje->getCadena("errorRegistroResolucion")." <br>";
	echo '<div style="text-align: center;">';
	foreach($listaError as $li){
		echo $li[0][0]." - ".$li[0][1]."<br>";
	}
	echo "</b></p></div></div><br><br>";
}



exit;








