<?php

class Agencia {

    private $_id_agencia,
            $_nombre_agencia,
            $_direccion_agencia,
            $_telefono_agencia,
            $_website_agencia,
            $_email_agencia,
            $_descripcion_agencia,
            $_imagen_agencia,
            $_deportes,
            $_tags_agencia;
            

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_agencia = $id;
            $sql = "SELECT * FROM agencias WHERE id_agencia = " . $this->_id_agencia;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_nombre_agencia = sql_htm($row['nombre_agencia']);
            $this->_direccion_agencia = $row['direccion_agencia'];
            $this->_telefono_agencia = $row['telefono_agencia'];
            $this->_website_agencia = $row['website_agencia'];
            $this->_email_agencia = $row['email_agencia'];
            $this->_descripcion_agencia = stripslashes(sql_htm($row['descripcion_agencia']));
            $this->_imagen_agencia = $row['image'];
            $this->_tags_agencia = $row['tags_agencia'];
            
            $sql2 = "SELECT GROUP_CONCAT(id_deporte SEPARATOR ',') as deportes FROM deportes_agencias WHERE id_agencia='".$id."'";
            $query2 = new Consulta($sql2);
            $row2 = $query2->verRegistro();
            $this->_deportes = $row2['deportes'];
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
