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
                
                $this->_describete  = $row['describete'];
                $this->_vivo_en     = $row['vivo_en'];
                $this->_telefono    = $row['telefono'];
                $this->_deporte_desde    = $row['deporte_desde'];
                $this->_deporte_favorito = $row['deporte_favorito'];
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
    
    public function __get($atributo) {
        return $this->$atributo;
    }
    
    public function __set($atributo, $value) {
        $this->$atributo = $value;
    }
    
}

?>