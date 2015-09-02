<?
include_once("../core/crypto/Encriptador.class.php");

$miCodificador=Encriptador::singleton();
echo $miCodificador->codificar("pruebascondor")."<br>";
echo $miCodificador->codificar("m4nt1s=App")."<br>";
echo $miCodificador->decodificar("JQAZaRn5EVQx8Ue-HPM79aIa")."<br>";
echo $miCodificador->decodificar("vQHLpUD5EVTjoPN4salmYgB7")."<br>";
echo $miCodificador->codificar("n4v4s10014")."<br>";
echo $miCodificador->codificar("123456")."<br>";

//echo $miCodificador->decodificar("pP2Gq4QUZ1PcO0JN28WA-wJ6")."<br>";


/*

$parametro=array("AwLSWHOR61DhZcTqkA==",
"CwKk33OR61C9BaWCkKKdcbc=",
"DwLlY3OR61B/gbFc",
"EwLQVHOR61DfS8OI/96/gEL0l9XuWw==",
"FwJ14HOR61DhdetkyM8whQ==",
"GwKxk3OR61C90avH6Fq2nbol5g==",
"HwI+DXOR61DMHj+OOwOsk7YAZg==");

foreach ($parametro as $valor){
	echo $miCodificador->decodificar($valor)."<br>";
}
*/


?>
