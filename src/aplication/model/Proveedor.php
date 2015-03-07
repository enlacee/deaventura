<?php

class Proveedor {

    private $_id_proveedor,
            $_nombre_proveedor,
            $_direccion_proveedor,
            $_telefono_proveedor,
            $_website_proveedor,
            $_email_proveedor,
            $_descripcion_proveedor,
            $_imagen_proveedor,
            $_tags_proveedor;

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_proveedor = $id;
            $sql = "SELECT * FROM proveedores WHERE id_proveedor = " . $this->_id_proveedor;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();
            
            $this->_nombre_proveedor = sql_htm($row['nombre_proveedor']);
            $this->_direccion_proveedor = $row['direccion_proveedor'];
            $this->_telefono_proveedor = $row['telefono_proveedor'];
            $this->_website_proveedor = $row['website_proveedor'];
            $this->_email_proveedor = $row['email_proveedor'];
            $this->_descripcion_proveedor = stripslashes(sql_htm($row['descripcion_proveedor']));
            $this->_imagen_proveedor = $row['image'];
            $this->_tags_proveedor = $row['tags_proveedor'];
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
