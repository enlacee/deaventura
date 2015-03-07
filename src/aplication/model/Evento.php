<?php

class Evento {

    private $_id_evento,
            $_titulo_evento,
            $_fecha_evento,
            $_lugar_evento,
            $_descripcion_evento,
            $_imagen_evento,
            $_tags,
            $_deportes;

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_evento = $id;
            $sql = "SELECT * FROM eventos WHERE id_evento = " . $this->_id_evento;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_titulo_evento = sql_htm($row['titulo_evento']);
            $this->_fecha_evento = $row['fecha_evento'];
            $this->_lugar_evento = $row['lugar_evento'];
            $this->_descripcion_evento = stripslashes(sql_htm($row['descripcion_evento']));
            $this->_imagen_evento = $row['image'];
            $this->_tags = $row['tags'];

            $sql2 = "SELECT GROUP_CONCAT(id_deporte SEPARATOR ',') as deportes FROM deportes_eventos WHERE id_evento='".$id."'";
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
