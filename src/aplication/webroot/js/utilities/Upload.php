<?php 
class Upload{

	public function upload_imagen($nombre, $temp, $destino, $tarchivo, $tamano ){
	
		if(!((strpos($tarchivo, "jpeg"))  ||  (strpos($tarchivo, "jpg")))){
			
			echo "<div id=error>La extensión no es valida ( Solo se permiten archivos .jpg  )</div>"; 
			
		}elseif($tamano > 2500000){
				echo "<div id=error>La extensión o el tamaño ".$tamano * 1024 ." del archivo no valida.</div>"; 
		}else{
			if(move_uploaded_file($temp,$destino.$nombre)){	
				return true;												
			}else{
				return false;
			}
		}			
	}
	
	public function upload_file($nombre, $temp, $destino, $tarchivo, $tamano){
		
		if($tamano > 2500000){
				echo "<div id=error>La extensión o el tamaño ".$tamano * 1024 ." del archivo no valida.</div>"; 
		}else if(($extencion == "pdf") || ($extencion == "doc") || ($extencion == "docx") ||  ($extencion == "jpeg") ||  ($extencion == "jpg") || ($extencion == "xlsx") || ($extencion == "xls")){
				
			if(move_uploaded_file($temp,$destino.$nombre)){	
				return true;											
			}else{
				return false;
				
			}	
					
		}
		
		else{
			echo "<div id=error>La extensión no es valida ( Solo se permiten archivos PDF, Word, JPG   )</div>"; 
		}			
	}	
}
?>