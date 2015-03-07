<?php

class Consulta {

    private $Consulta_ID = 0;
    private $Errno = 0;
    private $Error = "";

    function Consulta($sql = "") {
        if ($sql == "") {
            $this->Error = "No ha especificado una consulta SQL";
            $this->Errno = mysql_errno();
            return false;
        }
        $this->Consulta_ID = mysql_query($sql) or die("<div id='error'>" . mysql_error() . "<br><br> " . $sql . "<div>");

        if (!$this->Consulta_ID) {
            $this->Errno = mysql_errno();
            $this->Error = mysql_error();
        }

        //echo $sql;
        return $this->Consulta_ID;
    }

    function NumeroCampos() {
        return mysql_num_fields($this->Consulta_ID);
    }

    function nuevoId() {
        return mysql_insert_id();
    }

    function Nombretabla() {
        
        return mysql_field_table($this->Consulta_ID,0);
        
    }

    function flagscampo($numcampo) {
        return mysql_field_flags($this->Consulta_ID, $numcampo);
    }

    function NumeroRegistros() {
        return mysql_num_rows($this->Consulta_ID);
    }

    function nombrecampo($numcampo) {
        return mysql_field_name($this->Consulta_ID, $numcampo);
    }

    function tipocampo($numcampo) {
        return mysql_field_type($this->Consulta_ID, $numcampo);
    }

    function tamaniocampo($numcampo) {
        return mysql_field_len($this->Consulta_ID, $numcampo);
    }

    function VerRegistro() {
        return mysql_fetch_array($this->Consulta_ID);
    }

    function registrosAfectado() {
        return mysql_affected_rows();
    }
    
    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>