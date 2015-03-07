<?php

Class SeccionTag{
private  $_id, $_id_deporte, $_id_seccion_sitio, $_tag;
public function __construct($id = 0, Idioma $idioma = Null){
    $this->_id = $id;
    $this->_idioma = $idioma;

    if($this->_id > 0){

        $sql = " SELECT * FROM secciones_tags WHERE id_seccion_tag = '".$this->_id."' "; 

        $query = new Consulta($sql);

        if($row = $query->VerRegistro()){ 
             $this->_id =  $row['id_seccion_tag']; 
             $this->_id_deporte =  $row['id_deporte']; 
             $this->_id_seccion_sitio =  $row['id_seccion_sitio']; 
             $this->_tag =  $row['tag_seccion_tag']; 
        }
    }
}
public function __get($attribute){
    return	$this->$attribute;
}
}
?>