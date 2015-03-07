<?php
class Idioma{
	
	private $_id;
	private $_nombre;
	private $_archivo;
	
	public function __construct($id = 0){
		
		$this->_id = $id;	
		if($this->_id == 0){
			$this->_id = 1;			
		} 
		
		$sql = "SELECT * FROM idiomas WHERE id_idioma = '".$this->_id."'";
		$query = new Consulta($sql);
		
		
		if($query->NumeroRegistros() > 0){
			$row = $query->VerRegistro();
			$this->_nombre  = $row['nombre_idioma'];
			$this->_archivo = $row['archivo_idioma'];
		}		 		
	}
	
	public function __get($field){
		return $this->$field;
	}
	
	public function switchs($id = 1){
		$this->_id = $id;
		$query = new Consulta("SELECT * FROM idiomas WHERE id_idioma = '".$this->_id."'");
		$row   = $query->VerRegistro();
		$this->_nombre  = $row['nombre_idioma'];
		$this->_archivo = $row['archivo_idioma'];
	}
}

?>