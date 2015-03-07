<?php
require_once(_model_."Actividad.php");

class Paquete extends Actividad {

    private $_id_paquete,          
            $_itinerario,
            $_inclusiones,
            $_no_incluye,            
            $_dificultad;

    public function __construct($id = 0) {
        $this->_id_paquete = $id;
        if ($id > 0) {         
            
            $sql = "SELECT * FROM actividades_paquetes WHERE id_actividad_paquete = " . $this->_id_paquete;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();
            
            $this->_id_paquete      = $row['id_actividad_paquete'];
            $this->_id_actividad    = $row['id_actividad'];
            $this->_itinerario      = $row['itinerario_actividad_paquete'];
            $this->_inclusiones     = $row['inclusiones_actividad_paquete'];
            $this->_no_incluye      = $row['no_incluye_actividad_paquete'];          
            $this->_dificultad      = $row['dificultad_actividad_paquete'];
          
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