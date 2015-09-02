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

//Consulta Correo Estudiante
$cadena_sqlD = $this->sql->cadena_sql("consultarCorreosEstudiante",$_REQUEST['codigo']); 
$registrosD = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");


if($registrosD==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoConsulta");
	echo "</b></p></div>";
	$notificado = "N";
}



$mail = new phpmailer();




$mail->Host     = $this->miConfigurador->getVariableConfiguracion ( "mailHost" );
$mail->FromName = $this->miConfigurador->getVariableConfiguracion ( "mailFromName" );
$mail->From     = $this->miConfigurador->getVariableConfiguracion ( "mailFrom" );
$mail->Mailer   = $this->miConfigurador->getVariableConfiguracion ( "mailMailer" );
$mail->SMTPAuth = true;
$mail->Username = $this->miConfigurador->getVariableConfiguracion ( "mailUsername" );
$mail->Password = $this->miConfigurador->getVariableConfiguracion ( "mailPassword" );
$mail->Timeout  = intval ($this->miConfigurador->getVariableConfiguracion ( "mailTimeout" ));
$mail->Charset  = $this->miConfigurador->getVariableConfiguracion ( "mailCharset" );

if($adjuntos!=''){
	if(is_array($adjuntos)){
		foreach($adjuntos as $adj) $mail->addAttachment($adj);
	}
	else $mail->addAttachment($adjuntos,"resolucionIcetex.pdf");
}



//Cuerpo del mensaje
$mail->Body    = $cuerpo;
//Este el asunto
$mail->Subject = $tema;

$mail->IsHTML(true);

//las direcciones de envio
//Correo Personal
if($registrosD[0][0] != "") $mail->AddAddress($registrosD[0][0]);
//Correo Institucional
if($registrosD[0][1] != "") $mail->AddAddress($registrosD[0][1]);

/*
$mail->AddAddress("karrlyttos@hotmail.com");
$mail->AddAddress("ingenierocromeroa@gmail.com");
$mail->AddAddress("caromeroa@correo.udistrital.edu.co");
*/

if(!$mail->Send()) {
	//echo $this->lenguaje->getCadena("errorMail")."<br>";;
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
	
	$cadenaErr = $this->lenguaje->getCadena("errorMail")."<br>".'Mailer Error: ' . $mail->ErrorInfo;
	$this->miMensaje->addMensaje("22",":".$cadenaErr,"error");
	echo $this->miMensaje->getLastMensaje();
	
	
	$notificado = "N";
	$this->estado = 6;
	
} else {
	//echo "enviado";
	$notificado = "S";
	$this->estado = 5;
	
}


$mail->ClearAllRecipients();


//parametros para el update
$parametros = array();
$parametros['codigo'] = $_REQUEST['codigo'];
$parametros['notificado'] = $notificado;
$parametros['anio'] = substr($_REQUEST['periodo'], 0, 4);
$parametros['per'] = substr($_REQUEST['periodo'], 5, 1);


//Actualiza si envio o no correo
$cadena_sql = $this->sql->cadena_sql("actualizaNotificado",$parametros);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);


if($registros!=false){
	//echo '<div style="text-align: center"><p><b>';
	//echo $this->lenguaje->getCadena("errorNoActualiza");
	//echo "</b></p></div>";
	
	$this->miMensaje->addMensaje("23","errorNoActualiza","error");
	echo $this->miMensaje->getLastMensaje();
	
	exit;
}

$temaRegistro = 'NOTIFICAR ESTUDIANTE '.$temaRegistro.' ';

$this->registroLog($temaRegistro.$_REQUEST['codigo']);






