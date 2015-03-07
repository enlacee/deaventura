<?php 
// Proyecto: Sistema Develoweb
// Version: 1.0
// Programador: Walter Meneses
// Framework: Develoweb Version: 2.0
// Clase: Secciones

/** relaciones */
require_once _model_.'Seccion.php';

class Secciones{
	
	function getSecciones(){
		$data;
		$sql = "SELECT * FROM secciones ORDER BY id_seccion ";
		$query = new Consulta($sql);
		while($row = $query->VerRegistro()){
			$data[] = array(
				'id'		=> $row['id_seccion'],
				'nombre'	=> $row['nombre_seccion'],
				'url'		=> $row['url_seccion']
			);		
		}
		return $data; 	
	}
	
	function getSeccionesPorModulo($id_modulo){
		$data;
		$sql = "SELECT * FROM secciones WHERE id_modulo='".$id_modulo."' ";
		$query = new Consulta($sql);
		while($row = $query->VerRegistro()){
			$data[] = array(
				'id'		=> $row['id_seccion'],
				'nombre'	=> $row['nombre_seccion'],
				'url'		=> $row['url_seccion']
			);		
		}
		return $data; 	
	}
	
	function newSecciones(){			
		
		$sss = new Consulta("SELECT * FROM estados");
		$c = "<select name='id_estado'>";
		$c.= "<option value=''> Seleccione Estado</option>";	
			while($rss = $sss->VerRegistro()){ $c.= "<option value='".$rss[0]."'> ".$rss[1]." </option>"; }
		$c.="</select>";
		
		
		$matrix	= array(1 => $c);	
	
	 
		$sql = "SELECT id_seccion, id_estado,
					nombre_seccion as nombre_seccion,
					correo_seccion as correo_seccion,
					telefono_seccion as telefono_seccion,
					empresa_seccion as empresa_seccion, 
					fecha_seccion as fecha,
					descripcion_seccion as descripcion,
					observaciones_seccion as observacion
				FROM secciones WHERE id_seccion='".$id."'"; 
		$query = new Consulta($sql);
		Form::getForm($query,'new',"secciones.php",$matrix);
	 	
	}
	
	function editSecciones($id){		
		$querysecciones = new Consulta("SELECT * FROM secciones WHERE id_seccion='".$id."' ");
		$r= $querysecciones->VerRegistro();
		$sss = new Consulta("SELECT * FROM estados");
		$c = "<select name='id_estado'>";
		$c.= "<option value=''> Seleccione Estado</option>";	
			while($rss = $sss->VerRegistro()){ 
				$c .= "<option value='".$rss[0]."' ";
				if($rss[0] == $r['id_estado']){ $c.= " selected "; }
				$c .="> ".$rss[1]." </option>";  
			}
		$c.="</select>";		
		
		$matrix	= array(1 => $c);	
		
		$sql = "SELECT id_seccion, id_estado,
					nombre_seccion as nombre_seccion,
					correo_seccion as correo_seccion,
					telefono_seccion as telefono_seccion,
					empresa_seccion as empresa_seccion, 
					fecha_seccion as fecha,
					descripcion_seccion as descripcion,
					observaciones_seccion as observacion
				FROM secciones WHERE id_seccion='".$id."'"; 
		$query = new Consulta($sql);
		Form::getForm($query,'edit',"secciones.php",$matrix);		
	}
		 
	
	function addSecciones($id_usuario){	 
		
		$sql="INSERT INTO secciones VALUES('','".$id_usuario."','".$_POST['id_estado']."','".$_POST['nombre_seccion']."','".$_POST['correo_seccion']."','".$_POST['telefono_seccion']."','".$_POST['empresa_seccion']."','".$_POST['descripcion']."','".date('Y-m-d')."','".$_POST['observacion']."')";
		$Query = new Consulta($sql);
		$ID = mysql_insert_id();		
			 
	}
	
	function updateSecciones($id){
	 
		$sql=" UPDATE secciones SET 
						id_estado    		   = '".$_POST['id_estado']."' ,
						correo_seccion    = '".$_POST['correo_seccion']."',
						telefono_seccion  = '".$_POST['telefono_seccion']."',
						empresa_seccion   = '".$_POST['empresa_seccion']."',
						descripcion_seccion   	   = '".$_POST['descripcion']."',						
						observaciones_seccion     = '".$_POST['observacion']."'						
				WHERE id_seccion='".$id."' ";
		$update = new Consulta($sql);		
	
		echo "<div id='error'> La actualizacion se realizo Correctamente </div>";			
	}
	
	
	function listSecciones(){
		$sql = "SELECT id_seccion, c.nombre_seccion, c.empresa_seccion, c.correo_seccion, date_format(c.fecha_seccion,'%d-%m-%Y') as fecha, e.nombre_estado as estado 
				FROM secciones c, estados e
				WHERE c.id_estado = e.id_estado
				ORDER BY id_seccion DESC ";
		$query = new Consulta($sql);		
		echo Listado::VerListado($query, "secciones.php");	
	}
	
	function deleteSecciones($id){
		$sql = "DELETE FROM secciones WHERE id_seccion='".$id."' ";
		$query = new Consulta($sql);		
	}
 
}

?>