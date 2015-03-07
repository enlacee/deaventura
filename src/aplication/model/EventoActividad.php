<?php

require_once(_model_."Actividad.php");

class EventoActividad extends Actividad {

    private $_id_evento,
            $_reglamento,
            $_fecha;

    public function __construct($id = 0) {
        $this->_id_paquete = $id;
        if ($id > 0) {
            $this->_id_evento = $id;
            $sql = "SELECT * FROM actividades_eventos WHERE id_actividad_evento = " . $this->_id_evento;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_id_evento = $row['id_actividad_evento'];
            $this->_id_actividad    = $row['id_actividad'];
            $this->_reglamento = $row['reglamento_actividad_evento'];
            $this->_fecha = $row['cierre_inscripciones_actividad_evento'];          

            $sql2 = "SELECT GROUP_CONCAT(id_deporte SEPARATOR ',') as deportes FROM deportes_eventos WHERE id_evento='".$id."'";
            $query2 = new Consulta($sql2);
            $row2 = $query2->verRegistro();
            $this->_deportes = $row2['deportes'];

            parent::__construct($this->_id_actividad); 
        }
		
    }

    public function __get($atributo) {
        return $this->$atributo;
    }
   
    public function getPadre($attribute){

        return  parent::__get($attribute);       

    }

    public function getTarifas($id_actividad){
        $sql   = " SELECT * FROM tarifas WHERE id_actividad='".$id_actividad."' ";
        $query = new Consulta($sql);
        $datos_tarifas = array();
        
        while($row = $query->VerRegistro()){
            $datos_tarifas[] = array(                                  
                                'nombre'      => $row['nombre_tarifa'],
                                'precio'      => $row['precio_tarifa'],
                                'comision'    => $row['comision_porcentual_tarifa'],
                                'estado'    => $row['estado_tarifa']                                   
            );
        }
        return $datos_tarifas;  
    } 
}
?>
