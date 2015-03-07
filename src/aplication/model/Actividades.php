<?php

class Actividades{

    private $_msgbox;
    private $_id_deporte;

    public function __construct(Msgbox $msg = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id_deporte;
    }

    
}
?>
