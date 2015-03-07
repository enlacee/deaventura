<?php
require_once(_model_."Actividad.php");

class Salida extends Actividad {

    private $_id_salida, 
			$_id_actividad,
            $_itinerario,
            $_inclusiones,
            $_no_incluye,
            $_hora_partida,
            $_lugar_partida,
            $_cupos,
            $_cupos_minimos,
            $_dificultad,
            $_requisitos,
            $_recomendaciones,
            $_que_llevar,
            $_condiciones,
            $_cierre_inscripciones;

    public function __construct($id = 0) {
        
        if ($id > 0) {
            
            $this->_id_salida = $id;
            
            $sql = "SELECT * FROM actividades_salidas WHERE id_actividad_salida = " . $this->_id_salida;
            
            $query = new Consulta($sql);
            $row = $query->VerRegistro();
            
            $this->_id_salida       = $row['id_actividad_salida'];
			$this->_id_actividad    = $row['id_actividad'];
            $this->_itinerario      = $row['itinerario_actividad_salida'];
            $this->_inclusiones     = $row['inclusiones_actividad_salida'];
            $this->_no_incluye      = $row['no_incluye_actividad_salida'];
            $this->_hora_partida    = $row['hora_partida_actividad_salida'];
            $this->_lugar_partida   = $row['lugar_partida_actividad_salida'];
            $this->_cupos           = $row['cupos_actividad_salida'];
            $this->_cupos_minimos   = $row['cupos_minimos_actividad_salida'];
            $this->_dificultad      = $row['dificultad_actividad_salida'];
            $this->_requisitos      = $row['requisitos_actividad_salida'];
            $this->_recomendaciones = $row['recomendaciones_actividad_salida'];
            $this->_que_llevar      = $row['que_llevar_actividad_salida'];
            $this->_condiciones     = $row['condiciones_actividad_salida'];
            $this->_cierre_inscripciones = $row['cierre_inscripciones_actividad_salida'];

			parent::__construct($this->_id_actividad);

            $sql2 = "SELECT GROUP_CONCAT(id_deporte SEPARATOR ',') as deportes FROM deportes_eventos WHERE id_evento='".$id."'";
            $query2 = new Consulta($sql2);
            $row2 = $query2->verRegistro();
            $this->_deportes = $row2['deportes'];
			
			 
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