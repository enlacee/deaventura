<?php

class Lugar {

    private $_id_lugar,
            $_nombre_lugar,
            $_lat_lugar,
            $_lng_lugar;

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_lugar = $id;
            $sql = "SELECT * FROM lugares WHERE id_lugar = " . $this->_id_lugar;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_nombre_lugar = sql_htm($row['nombre_lugar']);
            $this->_lat_lugar = $row['lat_lugar'];
            $this->_lng_lugar = $row['lng_lugar'];
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
