<?php

class Organizacion {

    private $_id_organizacion,
            $_nombre_organizacion,
            $_website_organizacion,
            $_imagen_organizacion,
            $_descripcion_organizacion,
            $_telefono_organizacion,
            $_direccion_organizacion,
            $_email_organizacion;

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_organizacion = $id;
            $sql = "SELECT * FROM organizaciones WHERE id_organizacion = " . $this->_id_organizacion;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_nombre_organizacion = sql_htm($row['nombre_organizacion']);
            $this->_website_organizacion = $row['website_organizacion'];
            $this->_imagen_organizacion = $row['image'];
            $this->_descripcion_organizacion = stripslashes(sql_htm($row['descripcion_organizacion']));
            $this->_telefono_organizacion = $row['telefono_organizacion'];
            $this->_direccion_organizacion = $row['direccion_organizacion'];
            $this->_email_organizacion = $row['email_organizacion'];
            
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
