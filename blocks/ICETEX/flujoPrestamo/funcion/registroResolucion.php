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

//procesa mover el archivo

$this->moverArchivo();

//Obtiene array de codigos y valor individual para la resolucion

$this->procesarExcelCodigos();

//asigna valores

$parametros = array();
$parametros["archivo"] = $this->rutaArchivo;
$parametros["resolucion"] = $_REQUEST['resolucion'];
$parametros["valorTotal"] = $_REQUEST['valorTotal'];
$parametros["periodo"] = $_REQUEST['periodo'];
$parametros["anio"] = substr($_REQUEST['periodo'], 0, 4);
$parametros["per"] = substr($_REQUEST['periodo'], 5, 1);

$listaError = array();
$listaExito = array();

$errorUpdate = false;
$errorInsert = false;
$errorModalidad = false;
$errorFecha = false;
$errorValor = false;
$errorCodigo = false;
$bandera =0;
//procesa el listado registra la resolucion para cada uno del listado

if(!count($this->listadoCodigos)||count($this->listadoCodigos)<1){
	
	//echo '<br><br><div style="text-align: center;font-style: italic;color:red;">';
	//echo "<br>No existe coincidencia entre el número de resolución ingresada y el archivo cargado<br>";
	//echo "</div><br>";
	
	$this->miMensaje->addMensaje("43","errorResolucionCodigo","error");
	echo $this->miMensaje->getLastMensaje();
	
	exit;
}
foreach ($this->listadoCodigos as $lista){
	    $arrayp = array();
		
		if(isset($lista[0])) {
			$parametros["codigo"] = $lista[0];
			$_REQUEST["codigo"] = $lista[0];
		}		else{
			$parametros["codigo"] =0;
			$_REQUEST["codigo"] = 0;
		}
		if(isset($lista[1])) {
			$parametros["valorIndividual"] = $lista[1];
			$_REQUEST['valorIndividual'] = $lista[1];
		}else {
			$parametros["valorIndividual"]=0;
			$_REQUEST['valorIndividual'] = 0;
		}
		$parametros['aprobado'] = 'S';
		$parametros['identificacion'] = $lista[4];
		
		//Validar Codigo - Identificacion
		if($parametros["codigo"]==0){
			$errorCodigo = true;
		}
		
		
		//validar que el valor individual sea numerico
		if(!is_numeric($parametros["valorIndividual"])||strlen ($parametros["valorIndividual"])>7){
			$errorValor = true;
		}
		
		
		//validar Modalidad de Credito
		if(strlen ($lista[2])<1){
			$parametros['modalidadCredito'] = 0;
			$errorModalidad = true;
		}else $parametros['modalidadCredito'] = $lista[2];
		
		//validar Modalidad de Credito
            $fecha = $lista[3] ;
		if(!$this->validateDate($lista[3])){
			$parametros['fechaCredito'] = 0;
			$errorFecha = true;
		}else $parametros['fechaCredito'] = $lista[3];
		 
		
		
		
		if($errorValor==false&&$errorModalidad==false&&$errorFecha==false&&$errorCodigo==false){
				//Se agrega el cambio ya que no se habia tenido en cuenta que para hacer la solicitud el 
				//credito debe encontrarse aprobado
				//Insert
				
				
				//aprueba credito
				$cadena_sql = $this->sql->cadena_sql("aprobarCredito",$parametros);
				$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
				
				
				if($registros!=false){
					$errorInsert = true ;
				}
				
				//Update
				//Reegistra en la resolucion
				$cadena_sql = $this->sql->cadena_sql("registroResolucion",$parametros);
				$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);
				
				
				
		
				if($registros!=false){
					$errorUpdate = true;
				}
				
				
				
		}else{
			
			
			$errorInsert = true ;
			$errorUpdate = true;
			
		
		}
		
		$_REQUEST["valorConsulta"] = $_REQUEST["codigo"];
		
		
		if($errorUpdate==false&&$errorInsert==false){
			            $listaExito[$bandera]['IDENTIFICACION'] = $parametros['identificacion'];
                        $listaExito[$bandera]['CODIGO']=$parametros["codigo"];
                        $listaExito[$bandera]['valorResolucionIcetex'] = $parametros["valorIndividual"];
                        
                        //revisar si la matricula es diferida
                        
                        $cadena_sqlRefS = $this->sql->cadena_sql("consultarPagoReferenciaMatricula",$parametros);
                        $registrosRefS = $esteRecursoDB->ejecutarAcceso($cadena_sqlRefS,"busqueda");
                        
                        if($registrosRefS==false){
                        		
                        	$errorConsulta = true;
                        		
                        }
                        
                        
                        //Valor que se le devuelve al estudiante
                        $listaExito[$bandera]['valorPago'] = 0;
                        
                        //Valor que el estduainte pago a al universidad 
                        $listaExito[$bandera]['valorPagoEstudiante'] = 0;
                        
                        //Valor que le estduainte debe pagar
                        $listaExito[$bandera]['valorDebePago'] = 0;
                        
                        //Valor Matrícula
                        $listaExito[$bandera]['valorMatricula'] = 0;
                        
                        
                        $valorPagado = 0;
                        
                        //Si es matricula diferida
                        $listaExito[$bandera]['diferido'] = 'no';
                        if(count($registrosRefS)>1)	$listaExito[$bandera]['diferido'] = 'si';
                        
                        //DIFERIDO
                        
                        	foreach ($registrosRefS as $reg){
                        
                        		$listaExito[$bandera]['valorMatricula']+= $reg['EMA_VALOR'];
                        		if($reg['EMA_PAGO'] == 'S'){
                        			$valorPagado += $reg['EMA_VALOR'];
                        			
                        			$listaExito[$bandera]['valorPagoEstudiante'] += $reg['EMA_VALOR'];
                        		}
                        	}
                        	
                        	$sumaPagos = 0;
                        	$sumaPagos = $listaExito[$bandera]['valorPagoEstudiante'] + $listaExito[$bandera]['valorResolucionIcetex']; 
                        	
                        	if($sumaPagos > $listaExito[$bandera]['valorMatricula']){
                        		$listaExito[$bandera]['valorPago'] = $sumaPagos - $listaExito[$bandera]['valorMatricula'];  
                        	}
                        	
                        	if($sumaPagos < $listaExito[$bandera]['valorMatricula']){
                        		$listaExito[$bandera]['valorDebePago'] = $listaExito[$bandera]['valorMatricula'] -  $sumaPagos;
                        		
                        	}
                        	
                        	                        
                   
                        
                 $bandera++;
		}else{
			
			
			if($errorValor!=false) array_push($arrayp,array($parametros["codigo"],'Valor individual no se encuentra o inválido',$parametros['identificacion']));
			if($errorModalidad!=false) array_push($arrayp,array($parametros["codigo"],'Modalidad no se encuentra o inválida',$parametros['identificacion']));
			if($errorFecha!=false) array_push($arrayp,array($parametros["codigo"],'Fecha, formato válido DD/MM/YY',$parametros['identificacion']." ".$fecha." fecha valida en formato DD/MM/YY"));
			if($errorCodigo!=false) array_push($arrayp,array($parametros["codigo"],'Identificación no se encuentra o inválida, revise que el documento se encuentre registrado en el sistema',$parametros['identificacion']));
			if($errorInsert!=false) array_push($arrayp,array($parametros["codigo"],'Insertar, revise si ya se registró resolución para el estudiante',$parametros['identificacion']));
			if($errorUpdate!=false) array_push($arrayp,array($parametros["codigo"],'Actualizar',$parametros['identificacion']));
			
			array_push($listaError,$arrayp );
				
		}
		
		$errorUpdate = false;
		$errorInsert = false;
		$errorModalidad = false;
		$errorFecha = false;
		$errorValor = false;
		$errorCodigo = false;
}


//Error
if(count($listaError)>0){
	
	echo '<div ><p>';
	echo '<p >'.$this->lenguaje->getCadena("errorRegistroResolucion")."</p> <br>";
	echo '<div style="text-align: center;">';
	foreach($listaError as $li){
		echo '<b>'.$li[0][0]."</b> - <b>".$li[0][2]."</b>: ".$li[0][1]."<br>";
	}
	echo "</p></div></div><br><br>";
}

//Exitoso

if(count($listaExito)>0){
	
	$diferencias = '';
	$diferencias .= '<div style="text-align: center;"><p>';
	$diferencias .= '<p >'.$this->lenguaje->getCadena("exitoRegistroResolucion")." </p><br>";
	$diferencias .= '<div style="text-align: center;">';
	$diferencias .= '<table style="margin: 0 auto;">';
	
	//elemntos notificacion
	$tema = "Estado aprobación Crédito ICETEX";
	
	$cadena_sql = $this->sql->cadena_sql("consultarTextoNotificacion",$parametros);
	$registrosEmail = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
	
	
	if($registrosEmail==false){
		//echo "no es posible consultar email";
		$this->miMensaje->addMensaje("46","errorConsultarEmail","error");
		echo $this->miMensaje->getLastMensaje();
		
		exit;
	
	}
	
	
	
	
	foreach($listaExito as $li){
		
		$cuerpo = $registrosEmail[0][0];
		
		
		$_REQUEST["valorConsulta"] = $li['CODIGO'];
		$_REQUEST["codigo"] = $li['CODIGO'];
		
		$parametros['codigo'] = $li['CODIGO'];
		
		$diferencias .= '<tr><td>'.$li['CODIGO'].'-'.$li['IDENTIFICACION'].'</td><td>';
		
		
		$diferencias .= 'El Valor de la matrícula es <b>'.$li['valorMatricula'].'</b>';
		$diferencias .= '<br>El estudiante consignó <b>'.$li['valorPagoEstudiante'].'</b>';
		$diferencias .= '<br>El Icetex Giró <b>'.$li['valorResolucionIcetex'].'</b>';
		$diferencias .= '<br>El estudiante debe pagar a la universidad <b>'.$li['valorDebePago'].'</b>';
		
		

		$diferencias .= "</td></tr>";
		
		
		$this->notificarEstudiante($cuerpo, $tema , '', 'RESOLUCION ICETEX');
		$this->actualizarEstadoFlujo();
		
	}
	
	$diferencias .= "</table></div></div>";
	
	
	
	echo $diferencias;
	
	
	$rutaExcel = $this->crearExcelTesoreriaResolucion($listaExito);
	
	$temaT = "Recepcion Resolución Crédito Estudiantes";
	$cuerpoT = "<p>Se ha cargado una resolucion de crédito de estudiantes al sistema<br>el archivo PDF de la resolución se encuentra adjunto<br><br><b>El siguiente Listado se encuentra en la resolucion</b></p><br>";
	$cuerpoT .= $diferencias;
	
	
	
	$this->notificarTesoreria($listaExito,$cuerpoT,$temaT,$rutaExcel,'CARGA RESOLUCION');
	
}
			
			





exit;






