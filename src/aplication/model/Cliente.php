<?php
class Cliente {

    private $_id;
    private $_idFacebook;
    private $_nombre;
    private $_apellidos;
    private $_foto;
    private $_tipo_foto;
    private $_sexo;
    private $_email;
    private $_url;
    private $_logeado = FALSE;
    private $_tipo_usuario;
    private $_agencia;
    //adding
    private $_describete;
    private $fecha_registro_cliente;

    public function __construct($id = 0) { 
        $this->_id = $id;
        if ($this->_id > 0) {

            $sql = " SELECT * FROM clientes WHERE id_cliente = '" . $this->_id . "' ";
            $query = new Consulta($sql);

            if ($row = $query->VerRegistro()) {
                $this->_nombre      = $row['nombre_cliente'];
                $this->_idFacebook  = $row['id_facebook_cliente'];
                $this->_apellidos   = $row['apellidos_cliente'];
                $this->_foto        = $row['image'];
                $this->_tipo_foto   = $row['tipo_foto_cliente'];
                $this->_sexo        = $row['sexo_cliente'];
                $this->_email       = $row['email_cliente'];
                $this->_url         = $row['url_cliente'];
                $this->_tipo_usuario= $row['tipo_cliente'];
                
                $this->_fecha_registro_cliente = $row['fecha_registro_cliente'];
                $this->_describete  = $row['describete'];
                $this->_vivo_en     = $row['vivo_en'];
                $this->_telefono    = $row['telefono'];
                $this->_deporte_desde    = $row['deporte_desde']; 
                $this->_deporte_favorito = $this->_getDeporteFavorito(unserialize($row['deporte_favorito'])); 
                $this->_deporte_equipo_que_utilizo = $row['deporte_equipo_que_utilizo'];
                
                $this->_nivel = $row['nivel'];
            }
            
            $sql_agencia = "SELECT * FROM clientes_agencias WHERE id_cliente = '".$this->_id."'";
            $query_agencia = new Consulta($sql_agencia);
            if($query_agencia->NumeroRegistros() > 0){
                $row_agencia = $query->VerRegistro();
                $this->_agencia = new Agencia($row_agencia["id_agencia"]);
            }
        }
    }
    
    /*
     * get data filter with soport favorite (from client)
     */
    private function _getDeporteFavorito($arrayDeporteFavorito = false) {
        $array = array();

        if ($arrayDeporteFavorito != false) {
            $sql_agencia = "SELECT id_deporte,nombre_deporte FROM deportes WHERE estado_deporte = 1;";
            $query_agencia = new Consulta($sql_agencia);

            $dataListDeportes = array();                
            if ($query_agencia->NumeroRegistros() > 0) {
                while ($row2 = $query_agencia->VerRegistro()) {
                    $dataListDeportes[] = array(
                        'id_deporte' => $row2['id_deporte'],
                        'nombre_deporte' => $row2['nombre_deporte'],
                    );
                }
            }

            // FILL DATA
            foreach ($arrayDeporteFavorito as $key => $value) { // registros base (id deportes)                    
                foreach ($dataListDeportes as $indice => $valor) {
                    if ($value == $valor['id_deporte']) {
                        $array[] = array (
                            'id' => $value,
                            'name' => $valor['nombre_deporte']
                        );
                        $c++;
                        break;
                    }
                }
            }
        }

        return $array;
    }
    
    /**
     * Formart OUT date (mounth and year)
     * @param date $dateFechaRegistro
     * @return string 
     */
    private function _getFormatFechaRegistro($dateFechaRegistro) {
        
        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $dateFechaRegistro);       
        $FechaStamp = $fecha->format('U');  // Get date Time (INT) timestamp
        
        $rs = false;        
        if ($FechaStamp) {
            $ano = date('Y',$FechaStamp);
            $mes = date('n',$FechaStamp);
            $dia = date('d',$FechaStamp);
            $diasemana = date('w',$FechaStamp);
            $diassemanaN = array("Domingo","Lunes","Martes","Miércoles",
                           "Jueves","Viernes","Sábado");
            $mesesN = array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                      "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            //echo  $diassemanaN[$diasemana].", $dia de ". $mesesN[$mes] ." de $ano";
            $rs = $mesesN[$mes] . ' ' . $ano;
        }
        
        return $rs;
    }

    /**
     * Function public get Format date (mes y año) Started client
     * @return string
     */
    public function getFechaOfStarted()
    {
        return $this->_getFormatFechaRegistro($this->_fecha_registro_cliente);
    }
    
    public function __get($atributo) {
        return $this->$atributo;
    }
    
    public function __set($atributo, $value) {
        $this->$atributo = $value;
    }
    
}

?>