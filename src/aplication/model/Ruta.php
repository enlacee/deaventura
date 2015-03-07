<?php

class Ruta {

    private $_id_ruta,
            $_nombre_ruta,
            $_lugar_ruta,
            $_descripcion_ruta_p,
            $_descripcion_ruta_s,
			$_tags,
            $_lat_ruta,
            $_lng_ruta;

    public function __construct($id1 = 0, $id = 0, $flag = TRUE) {
        if ($id > 0 && $id1 > 0) {
            $this->_id_ruta = $id;
            if ($flag) {
                $sql = "SELECT * FROM rutas INNER JOIN lugares USING(id_lugar) WHERE id_lugar = " . $this->_id_ruta . " AND id_deporte = " . $id1;
                $query = new Consulta($sql);
                $row = $query->VerRegistro();
                $descripcion_p = sql_htm($row['descripcion_ruta']);
                $descripcion_s = sql_htm($row['descripcion_ruta_s']);
            }else{
                $sql = "SELECT * FROM rutas_otros INNER JOIN lugares USING(id_lugar) WHERE id_lugar = " . $this->_id_ruta . " AND id_modalidad = " . $id1;
                $query = new Consulta($sql);
                $row = $query->VerRegistro();
                $descripcion_p = sql_htm($row['descripcion_rutas_otros']);
                $descripcion_s = sql_htm($row['descripcion_rutas_otros_s']);
            }

            $this->_nombre_ruta = sql_htm($row['nombre_lugar']);
            $this->_lugar_ruta = $row['ubicacion_lugar'];
            $this->_descripcion_ruta_p = stripslashes($descripcion_p);
            $this->_descripcion_ruta_s = stripcslashes($descripcion_s);
            $this->_lat_ruta = $row['lat_lugar'];
            $this->_lng_ruta = $row['lng_lugar'];
			$this->_tags = $row['tags'];
            $this->_image = $row['image'];

        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
