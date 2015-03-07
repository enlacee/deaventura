<?php

class Modalidad {
    
    private $_deporte;
    private $_nombre_modalidad;
    private $_descripcion_modalidad;

    public function __construct($id = 0) {
        if ($id > 0) {
            //Cambie: en el sql id_deporte por id_modalidad
            $sql = "SELECT * FROM modalidades WHERE id_modalidad = " . $id;
            $query = new Consulta($sql);
            
            $row = $query->VerRegistro();
            $this->_deporte = new Deporte($row['id_deporte']);
            $this->_nombre_modalidad = sql_htm($row['nombre_modalidad']);
            $this->_descripcion_modalidad = sql_htm($row['descripcion_modalidad']);
            
            
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
