<?php
class Pagina{
	
	private $_id, $_titulo , $_descripcion ;
	
	public function __construct($id = 0){
		$this->_id = $id;	
		
		if($this->_id > 0){			 	

			$query = new Consulta(" SELECT * FROM paginas WHERE id_pagina = '".$this->_id."'");			
			if($row = $query->VerRegistro()){ 			
				$this->_titulo 	 = $row['titulo_pagina'];
				$this->_descripcion 	 = $row['descripcion_pagina'];			
			}
			
		}					
	}
	
	public function __get($attribute){
		return	$this->$attribute;
	}
} ?>