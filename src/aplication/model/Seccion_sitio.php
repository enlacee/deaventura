<?php

class Seccion_sitio {

    private $_id_seccion_sitio;
    private $_nombre_seccion_sitio;

    public function __construct($id = 0) {
        if ($id > 0) {
            $sql = "SELECT * FROM secciones_sitio WHERE rel_deporte_secciones_sitio = '1' AND id_seccion_sitio = " . $id;
            $query = new Consulta($sql);

            $row = $query->VerRegistro();
            $this->_id_seccion_sitio = $row['id_seccion_sitio'];
            $this->_nombre_seccion_sitio = sql_htm($row['nombre_seccion_sitio']);
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
