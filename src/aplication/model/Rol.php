<?php 
// Proyecto: Sistema Develoweb
// Version: 1.0
// Programador: Walter Meneses
// Framework: Develoweb Version: 2.0
// Clase: Proyectos

/** relaciones */

class Rol{

	private $_id;
	private $_servicio;
	private $_nombre;
	private $_descripcion; 

	public function __construct($id = 0){
		$this->_id = $id;
		if($this->_id > 0){
			$sql = " SELECT * FROM roles WHERE  id_rol ='".$this->_id."'  ";
			$query = new Consulta($sql);
			if($query->NumeroRegistros() > 0){
				$row = $query->VerRegistro(); 
				$this->_nombre 		  	= $row['nombre_rol'];
				$this->_descripcion     = $row['descripcion_rol'];
			}				
		} 
	}
	
	public function getId(){
		return $this->_id;
	}
	 
	public function getNombre(){
		return $this->_nombre;
	}
	 
	public function getDescripcion(){
		return $this->_descripcion;
	}
	
	public function __toString()
	{
		return $this->_nombre ;
	}
	
}
?>