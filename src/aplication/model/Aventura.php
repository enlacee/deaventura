<?php

class Aventura {

    private $_id_aventura,
            $_cliente,
            $_id_modalidad,
            $_id_agencia,
            $_titulo_aventura,
            $_lugar_aventura,
            $_descripcion_aventura,
            $_lat_aventura,
            $_lng_aventura,
            $_fecha_creacion_aventura,
            $_cant_likes_aventura,
            $_cant_coments_aventura,
            $_deporte,
            $_cant_images,
            $_archivos,
            $_cant_visitas_aventura,
            $_agencia;

    public function __construct($id) {
        if ($id > 0) {
            $this->_id_aventura = $id;
            $sql = "SELECT * FROM aventuras WHERE id_aventura = " . $this->_id_aventura; 
            $query = new Consulta($sql);

            if ($query->NumeroRegistros() > 0) {
                $row = $query->VerRegistro();

                $this->_cliente = new Cliente($row['id_cliente']);
                $this->_agencia = new Agencia($row['id_agencia']);
                
                $this->_id_modalidad = $row['id_modalidad'];
                $this->_id_agencia = $row['id_agencia'];
                $this->_titulo_aventura = $row['titulo_aventura'];
                $this->_lugar_aventura = $row['lugar_aventura'];
                $this->_descripcion_aventura = $row['descripcion_aventura'];
                $this->_lat_aventura = $row['lat_aventura'];
                $this->_lng_aventura = $row['lng_aventura'];
                $this->_fecha_creacion_aventura = $row['fecha_creacion_aventura'];
                $this->_cant_likes_aventura = $row['cant_likes_aventura'];
                $this->_cant_coments_aventura = $row['cant_coment_aventura'];
                $this->_cant_visitas_aventura = $row['cant_coment_aventura'];

                $sql2 = "SELECT * FROM modalidades WHERE id_modalidad = " . $this->_id_modalidad;
                $query2 = new Consulta($sql2);
                $row2 = $query2->VerRegistro();
                $this->_deporte = new Deporte($row2['id_deporte']);

                $sql3 = "SELECT * FROM aventuras_archivos WHERE id_aventura = " . $this->_id_aventura . " ORDER BY orden_aventuras_archivos ASC";
                $query_archivos = new Consulta($sql3);
                $this->_cant_images = $query_archivos->NumeroRegistros();
                while ($row_archivos = $query_archivos->VerRegistro()) {
                    $this->_archivos[] = array(
                        'id_aventuras_archivo' => $row_archivos['id_aventuras_archivo'],
                        'nombre_aventuras_archivos' => $row_archivos['nombre_aventuras_archivos'],
                        'tipo_aventuras_archivo' => $row_archivos['tipo_aventuras_archivo'],
                        'titulo_aventuras_archivo' => $row_archivos['titulo_aventuras_archivo'],
                        'comentario_aventuras_archivo' => $row_archivos['comentario_aventuras_archivo']
                    );
                }
            }
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $value) {
        $this->$atributo = $value;
    }

}

?>
