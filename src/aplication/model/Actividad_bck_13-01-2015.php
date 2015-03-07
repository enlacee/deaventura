<?php
abstract class Actividad{

    private $_id_actividad,
            $_cliente,
            $_tipo_actividad,
            $_agencia,
            $_nombre,
            $_descripcion,
            $_fecha,
            $_imagen,
            $_video,
            $_lugar,
            $_latitud,
            $_longitud,
            $_estado,
            $_deportes,
            $_tarifas,
            $_tags;
    

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_actividad = $id;
            $sql = "SELECT * FROM eventos WHERE id_evento = " . $this->_id_actividad;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();
            $this->_cliente     = new Cliente($row['id_cliente']);
            $this->_tipo_actividad = new TipoActividad($row['id_tipo_actividad']);
            $this->_agencia     = new Agencia($row['id_agencia']);
            $this->_nombre      = $row['nombre_actividad'];
            $this->_descripcion = $row['descripcion_actividad'];
            $this->_fecha       = $row['fecha_actividad'];
            $this->_imagen      = $row['imagen_actividad'];
            $this->_video       = $row['video_actividad'];
            $this->_lugar       = $row['lugar_actividad'];
            $this->_latitud     = $row['latitud_actividad'];
            $this->_longitud    = $row['longitud_actividad'];
            $this->_estado      = $row['estado_actividad'];
            $this->_tags        = $row['tags_actividad'];
            
            $sql2 = "SELECT GROUP_CONCAT(id_deporte SEPARATOR ',') as deportes FROM actividades_deportes WHERE id_actividad='".$id."'";
            $query2 = new Consulta($sql2);
            $row2 = $query2->verRegistro();
            $this->_deportes = $row2['deportes'];
            
            $sql3 = "SELECT FROM tarifas WHERE id_actividad='".$id."'";
            $query3 = new Consulta($sql2);
            while($row3 = $query2->verRegistro()){
                $this->_tarifas[] = array(
                    'id' => $row3['id_tarifa'],
                    'nombre' => $row3['nombre_tarifa'],
                    'descripcion' => $row3['descripcion_tarifa'],
                    'precio' => $row3['precio_tarifa'],
                    'oferta' => $row3['precio_oferta_tarifa'],
                    'comision_fija' => $row3['comision_fija_tarifa'],
                    'comision_porcentual' => $row3['comision_porcetual_tarifa']
                );
            };
        }
		
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}
?>
