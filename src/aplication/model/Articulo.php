<?php

class Articulo {

    private $_id, $_deporte, $_nombre, $_descripcion, $_imagen, $_order, $_tags;

    public function __construct($id = 0, Idioma $idioma = Null) {
        $this->_id = $id;
        $this->_idioma = $idioma;

        if ($this->_id > 0) {

            $sql = " SELECT * FROM articulos WHERE id_articulo = '" . $this->_id . "' ";

            $query = new Consulta($sql);

            if ($row = $query->VerRegistro()) {
                $this->_id = $row['id_articulo'];
                $this->_deporte = new Deporte($row['id_deporte']);
                $this->_nombre = $row['nombre_articulo'];
                $this->_descripcion = $row['descripcion_articulo'];
                $this->_imagen = $row['imagen_articulo'];
                $this->_order = $row['order_articulo'];
                $this->_tags = $row['tags_articulo'];
            }
        }
    }

    public function __get($attribute) {
        return $this->$attribute;
    }

}

?>