<?php 
class Modulo{

	private $_id, $_nombre;
	
	public function __construct($id = 0){
		$this->_id = $id;
		
		if($this->_id > 0){
			$sql = " SELECT * FROM modulos WHERE id_modulo = '".$this->_id."' ";
			$query = new Consulta($sql);
			if($row = $query->VerRegistro()){
				$this->_id 	   	= $row['id_modulo'];
				$this->_nombre 	= $row['nombre_modulo'];
			}			
		}
	}
	
	public function getId(){
		return $this->_id;
	}

	public function getNombre(){
		return $this->_nombre;
	}
	public function __toString(){
		return $this->_id;
	}
		
}
?>