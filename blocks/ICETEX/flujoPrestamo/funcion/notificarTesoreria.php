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



$mail->addAttachment($this->rutaArchivo,"resolucionIcetex.pdf");         // Add attachments

if($adjuntos!=''){
	if(is_array($adjuntos)){
		foreach($adjuntos as $adj) $mail->addAttachment($adj);;
	}
	else $mail->addAttachment($adjuntos);
}

if($tema=='') $tema = "Recepcion Resolución Credito Estudiantes";



//Cuerpo del mensaje
$mail->Body    = $cuerpo;
//Este el asunto
$mail->Subject = $tema;

$mail->IsHTML(true);


$cadena_sql = $this->sql->cadena_sql("consultarEmailNombre",'financiera');
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
	
if($registros&&count($registros)>0){
	foreach ($registros as $reg){
		$mail->AddAddress($reg[0]);
	}
}
			


if(!$mail->Send()) {
	//echo $this->lenguaje->getCadena("errorMail")."<br>";
	//echo 'Mailer Error: ' . $mail->ErrorInfo;
	$cadenaErr = $this->lenguaje->getCadena("errorMail")."<br>".'Mailer Error: ' . $mail->ErrorInfo;
	$this->miMensaje->addMensaje("22",":".$cadenaErr,"error");
	echo $this->miMensaje->getLastMensaje();
	
	exit;
	
	
}
$mail->ClearAllRecipients();

$temaRegistro = 'NOTIFICAR TESORERIA '.$temaRegistro.' ';

$this->registroLog($temaRegistro);











