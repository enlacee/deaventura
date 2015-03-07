<?php
class Thumbnail{
	
	var $image    = "";
	var $width    = 0;
	var $height   = 0;
	var $new_width;
	var $new_height;
	var $ruta     = "";
	var $error    = "";
	var $extencion= "";
	var $name     = "";
	var $thumbnail;
	var $texto    = "";
	var $fontColor;	
						
	function Thumbnail($file){		
	 	if(file_exists($file)){
			$camino_nombre=explode("/",$file);
			$this->name   = end($camino_nombre);//nombre del thumbnail			
			$extension    = explode(".",$this->name);//Separamos las extenciones de archivos para definir el tipo.
			$ext = count($extension)-1;  
			$this->extencion = $extension[$ext];
			
			//Determinamos las extenciones permitidas. 
			if($this->extencion == "jpg" || $this->extencion == "jpeg"  || $this->extencion == "bmp"  || $this->extencion == "JPG"  || $this->extencion == "JPEG"){ 
				$this->image = ImageCreateFromJPEG($file);	
				//echo "<br>imagejpg ".$this->image; 			
			}else if($this->extencion == "gif"){ 
				$this->image = ImageCreateFromGIF($file); 
				//echo "<br>imagegif ".$this->image; 			
			}else if($this->extencion == "png"){ 
				$this->image = ImageCreateFromPNG($file); 
				//echo "<br>imagepng ".$this->image; 			
			}else if($this->extencion == "wbmp"){
				$this->image = ImageCreateFromWBMP($file); 
				//echo "<br>imagewbmp ".$this->image; 			
			}else{ 
				$error= "Error, extencion no permitida"; 
				return $this->error; 
			}
			
			// Aquí iniciamos valores por defecto			
			$this->ruta 	= substr($file,0,strlen($file)-strlen($this->name)); //ruta de la imagen
			$this->width   	= imagesx($this->image);//ancho 
			$this->height 	= imagesy($this->image);//alto
			$this->SetfontColor("naranja");  		//color del texto
			
		}else{
			$this->error="No existe el archivo de imagen";
			return $this->error;
		}		
	}
	
	function SetRuta($url=""){
		if(!empty($url)){
			$this->ruta=$url;
		}		
	}
	
	function SetName($name=""){
		if(!empty($name)){
			$this->name=$url;
		}		
	}
	function SetTexto($texto){
		if(!empty($texto)){
			$this->texto = $texto;
		}		
	}
	
	function SetfontColor($color){
	
		switch($color){
			case "blanco":
				$this->fontColor	= 	imagecolorallocate($this->image, 255, 255, 255);
			break;
			case "negro":
				$this->fontColor 	= 	imagecolorallocate($this->image, 0, 0, 0);
			break;
			case "azul":
				$this->fontColor 	= 	imagecolorallocate($this->image, 0, 0, 255);			
			break;
			case "naranja":
				$this->fontColor	=	imagecolorallocate($this->image, 251, 87, 9);
			break;
			case "transparente":
				$this->fontColor	= 	imagecolorallocate($this->image, 219, 224, 229);
			break;
			case "gris":
				$this->fontColor 	= 	imagecolorallocate($this->image, 110, 106, 110);
			break;
			case "grisclaro":
				$this->fontColor	= 	imagecolorallocate($this->image, 180, 180, 180); 
			break;
			case "lavender":
				$this->fontColor 	= 	imagecolorallocate($this->image, 180, 180, 250); 
			break;
			default:
				$this->fontColor 	= 	imagecolorallocate($this->image, 180, 180, 180); 
			break;
		}
	}
			
	function CreateThumbnail($width=0, $height=0){
		
		if($width > 0 || $height > 0 ){
			if(!empty($this->image)){		
				
				// intentamos escalar la imagen original a la medida que nos interesa
				
				/* EN CASO DE ANCHURA (tengo la altura) */
				$wratio = ($this->height / $height);
				$this->new_width = round($this->width / $wratio);
			
				/* EN CASO DE ALTURA (teniendo la anchura)*/
				$hratio=($this->width / $width);
				$this->new_height = round($this->height / $hratio);
				//$this->new_height = ($width * $this->height) / $this->width ; // tamaño proporcional 				
				
				if($this->new_height > $height && $this->new_width <= $width){
					$this->new_height = $height;
				}else if($this->new_width > $width && $this->new_height <= $height){
					$this->new_width = $width;
				}else{
					$this->error = "Error al Redimensionar";
					return $this->error;
				}
				
				// esta será la nueva imagen reescalada
				$this->thumbnail = imagecreatetruecolor($this->new_width,$this->new_height);
									
				// con esta función la reescalamos
				imagecopyresampled ($this->thumbnail, $this->image, 0, 0, 0, 0, $this->new_width,$this->new_height,$this->width,$this->height)or die ("error");
				
				
				//aqui verificamos que si contiene texto
				if($this->texto!=""){					
					imagestring($this->thumbnail,2,0,0,$this->texto,$this->fontColor);
				}
				
				//le colocamos la cabezera
				header("Content-type: image/jpeg"); 	
				
				// voilà la salvamos con el nombre y en el lugar que nos interesa.
			/*	if(!empty($this->ruta)){
					if(!(is_dir($this->ruta))){					
						//$this->error="ERROR: La ruta no es valida";
						//return $this->error;
						imagejpeg($this->thumbnail,$this->ruta,90);
					}else{
						imagejpeg($this->thumbnail,$this->ruta,90);
					}					
				}else{
					
				}
			*/
				imagejpeg($this->thumbnail,'',100); 
				imagedestroy($this->image); 
				
				return $this->thumbnail; 
				
			}else{
				$error="No se Pudo Inicializar la Imagen por falta de Parametros";
				$this->error=$error;
				return $this->error;
			}	
		}else{
			$this->error="No se Pudo trabajar Imagen, Verifique que la imagen Existe";
			return $this->error;		
		}			
	}
}

?>