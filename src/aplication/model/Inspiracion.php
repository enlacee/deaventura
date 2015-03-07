<?php

class Inspiracion {

    private $_id, $_id_deporte, $_insight, $_imagen, $_order, $_tags;

    public function __construct($id = 0, Idioma $idioma = Null) {
        $this->_id = $id;
        $this->_idioma = $idioma;

        if ($this->_id > 0) {

            $sql = " SELECT * FROM inspiraciones WHERE id_inspiracion = '" . $this->_id . "' ";

            $query = new Consulta($sql);

            if ($row = $query->VerRegistro()) {
                $this->_id = $row['id_inspiracion'];
                $this->_deporte = new Deporte($row['id_deporte']);
                $this->_insight = $row['insight_inspiracion'];
                $this->_imagen = $row['imagen_inspiracion'];
                $this->_order = $row['order_inspiracion'];
                $this->_tags = $row['tags_inspiracion'];
            }
        }
    }

    public function __get($attribute) {
        return $this->$attribute;
    }

}

?>