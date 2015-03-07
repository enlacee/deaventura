<?php

class secciones_sitio {

    private $_msgbox;

    public function __construct(Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
    }

    public function list_secciones_sitio() {
        $sql = " SELECT * FROM secciones_sitio WHERE rel_deporte_secciones_sitio = '1'";
        $query = new Consulta($sql);
        $deporte = new Deporte($_GET["cat"]);
        while ($rowp = $query->VerRegistro()) {
            $arr[] = array(
                'nombre' => sql_htm($rowp['nombre_seccion_sitio']),
                'deporte' => $deporte->__get("_nombre_deporte")
            );
        }
    }
    
    public function get_secciones_sitio(){
        $sql = " SELECT * FROM secciones_sitio WHERE rel_deporte_secciones_sitio = '1' ORDER BY orden_seccion_sitio";
        $query = new Consulta($sql);
        while ($row = $query->VerRegistro()) {
            $arr[] = array(
                'id_seccion_sitio' => $row['id_seccion_sitio'],
                'nombre_seccion_sitio' => sql_htm($row['nombre_seccion_sitio'])
            );
        }
        return $arr;
    }

}
?>
