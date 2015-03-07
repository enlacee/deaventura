<?

class FTP{
	
	var $id_ftp;
	
	function ConectarFTP($server, $user, $password, $port, $modo ){
	
		if(!$this->id_ftp=ftp_connect($server, $port)){
			echo "no se puedo realizar la conexion ftp";
		}else{
			if(!ftp_login($this->id_ftp, $user, $password)){
				echo "no se puedo logear, usuario o pasrowd incorrecto";
			}else{
				ftp_pasv($this->id_ftp, $modo);
			} 			
		}
	}
	
	function SubirArchivo($archivo_remoto,$archivo_local){
		if( !ftp_put($this->id_ftp, $archivo_remoto, $archivo_local, FTP_BINARY) ){
			echo "no se pudo subir el fichero ".$archivo_remoto;			
		}		
	}
	
	function GetRuta(){
		$Directorio=ftp_pwd($this->id_ftp); //Devuelve ruta actual p.e. "/home/willy"		
		return $Directorio; //Devuelve la ruta a la función
	}
	
	function AccesoRuta($ruta){
		if (!ftp_chdir($this->id_ftp, $ruta)){
			echo " no se puedo ingresar a la ruta ";
		}
	}
	
	function CerrarFTP(){
		ftp_quit($this->id_ftp); //Cierra la conexion FTP
	}

}
?>
