<?PHP
/**
 * IMPORTANTE
 * Este archivo debe generarse en el momento de la instalación
 * de SARA.
 *
 * Mientras se realiza el instalador se deben colocar de forma
 * manual los datos.
 *
 * El acceso a este archivo debe ser denegado. En servidores apache,
 * en el archivo .htaccess colocar:
 * # Proteger config.inc.php
 * <files config/config.inc.php>
 * 	order allow,deny
 * 	deny from all
 * </files>
 *
 *  @todo En sistemas UNIX/Linux este archivo debe tener permisos 640, una
 *  medida adicional en sistemas en producción, podría ser el colocar
 *  este archivo en una carpeta que no esté en el directorio de
 *  documentos de apache; y encriptarlo utilizando las llaves de usuario
 *  registradas en el sistema operativo.
 *
 */

class ArchivoConfiguracion{
	
	private static $instance;

	/**
	 * Contiene los datos básicos de acceso a la base de datos principal del
	 * framework. Este usuario debe tener solo permisos de lectura.
	 *
	 * @var string[]
	 */

	var $conf;

	private function __construct(){

		$this->conf=array();
		$this->variable();		
	}
	
	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
	
	public function setConectorDB($objeto){
		$this->fabricaConexiones=$objeto;
	}

	function getConf(){
		return $this->conf;
	}

	/**
	 * Rescata las variables de configuración que se encuentran en config.inc.php y
	 * en la base de datos principal (cuyos datos de conexión están en config.inc.php).
	 * Los datos son cargados en el arreglo $configuracion
	 * @param Ninguno
	 * @return number
	 */

	function variable() {
			
		require_once("core/crypto/Encriptador.class.php");
		require_once("core/crypto/aes.class.php");
		require_once("core/crypto/aesctr.class.php");

		$this->cripto = Encriptador::singleton();
		$this->abrirArchivoConfiguracion();
		return 0;
	}

	private function abrirArchivoConfiguracion($ruta=""){

		$this->fp = fopen("config/config.inc.php", "r");
		if (!$this->fp) {
			return false;
		}

		$this->i = 0;
		while (!feof($this->fp)) {
			$this->linea = $this->cripto->decodificar(fgets($this->fp, 4096), "");
			$this->i++;
			switch ($this->i) {
				case 3:
					$this->conf["dbsys"] = $this->linea;
					break;

				case 4:
					$this->conf["dbdns"] = $this->linea;
					break;

				case 5:
					$this->conf["dbpuerto"] = $this->linea;
					break;
						
				case 6:
					$this->conf["dbnombre"] = $this->linea;
					break;
				case 7:
					$this->conf["dbusuario"] = $this->linea;
					break;
				case 8:
					$this->conf["dbclave"] = $this->linea;
					break;
				case 9:
					$this->conf["dbprefijo"] = $this->linea;
					break;
			}
			if ($this->i == 9) {
				break;
			}

		}
		fclose($this->fp);
	}
}
?>
