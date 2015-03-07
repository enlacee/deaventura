<?php 
// Proyecto: Sistema Develoweb
// Version: 1.0
// Programador: Walter Meneses
// Framework: Develoweb Version: 2.0
// Clase: Proyectos

/** relaciones */ 
require_once(_model_."Rol.php");

class Roles{
	
	function getRoles(){
		$data;
		$sql = "SELECT * FROM roles ORDER BY id_rol DESC ";
		$query = new Consulta($sql);
		while($row = $query->VerRegistro()){
			$data[] = array(
						'id'		=> $row['id_rol'],
						'nombre'	=> $row['nombre_rol']
			);		
		}
		return $data; 	
	}
	
	function newRoles(){			
		
		$sss = new Consulta("SELECT * FROM estados");
		$c = "<select name='id_estado'>";
		$c.= "<option value=''> Seleccione Estado</option>";	
			while($rss = $sss->VerRegistro()){ $c.= "<option value='".$rss[0]."'> ".$rss[1]." </option>"; }
		$c.="</select>";
		
		
		$matrix	= array(1 => $c);	
	
	 
		$sql = "SELECT id_rol, id_estado,
					nombre_rol as nombre_rol,
					correo_rol as correo_rol,
					telefono_rol as telefono_rol,
					empresa_rol as empresa_rol, 
					fecha_rol as fecha,
					descripcion_rol as descripcion,
					observaciones_rol as observacion
				FROM roles WHERE id_rol='".$id."'"; 
		$query = new Consulta($sql);
		Form::getForm($query,'new',"roles.php",$matrix);
	 	
	}
	
	function editRoles($id){		
		$queryroles = new Consulta("SELECT * FROM roles WHERE id_rol='".$id."' ");
		$r= $queryroles->VerRegistro();
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
		
		$sql = "SELECT id_rol, id_estado,
					nombre_rol as nombre_rol,
					correo_rol as correo_rol,
					telefono_rol as telefono_rol,
					empresa_rol as empresa_rol, 
					fecha_rol as fecha,
					descripcion_rol as descripcion,
					observaciones_rol as observacion
				FROM roles WHERE id_rol='".$id."'"; 
		$query = new Consulta($sql);
		Form::getForm($query,'edit',"roles.php",$matrix);		
	}
		 
	
	function addRoles($id_usuario){	 
		
		$sql="INSERT INTO roles VALUES('','".$id_usuario."','".$_POST['id_estado']."','".$_POST['nombre_rol']."','".$_POST['correo_rol']."','".$_POST['telefono_rol']."','".$_POST['empresa_rol']."','".$_POST['descripcion']."','".date('Y-m-d')."','".$_POST['observacion']."')";
		$Query = new Consulta($sql);
		$ID = mysql_insert_id();		
			 
	}
	
	function updateRoles($id){
	 
		$sql=" UPDATE roles SET 
						id_estado    		   = '".$_POST['id_estado']."' ,
						correo_rol    = '".$_POST['correo_rol']."',
						telefono_rol  = '".$_POST['telefono_rol']."',
						empresa_rol   = '".$_POST['empresa_rol']."',
						descripcion_rol   	   = '".$_POST['descripcion']."',						
						observaciones_rol     = '".$_POST['observacion']."'						
				WHERE id_rol='".$id."' ";
		$update = new Consulta($sql);		
	
		echo "<div id='error'> La actualizacion se realizo Correctamente </div>";			
	}
	
	
	function listRoles(){
		$sql = "SELECT id_rol, c.nombre_rol, c.empresa_rol, c.correo_rol, date_format(c.fecha_rol,'%d-%m-%Y') as fecha, e.nombre_estado as estado 
				FROM roles c, estados e
				WHERE c.id_estado = e.id_estado
				ORDER BY id_rol DESC ";
		$query = new Consulta($sql);		
		echo Listado::VerListado($query, "roles.php");	
	}
	
	function deleteRoles($id){
		$sql = "DELETE FROM roles WHERE id_rol='".$id."' ";
		$query = new Consulta($sql);		
	}
 
}

?>