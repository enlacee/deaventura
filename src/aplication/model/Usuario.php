<?php

// Proyecto: Sistema Develoweb
// Version: 1.0
// Programador: Walter Meneses
// Framework: Develoweb Version: 2.0
// Clase: Usuario

/** relaciones */
require_once _model_ . 'Rol.php';
require_once _model_ . 'Seccion.php';

class Usuario {

    private $_id;
    private $_nombre;
    private $_apellidos;
    private $_email;
    private $_rol;
    private $_login = "Visitante";
    private $_password;
    private $_fecha_ingreso;
    private $_secciones;
    private $_logeado = FALSE;
    private $_foto;

    public function __construct($id = 0) {

        $this->_id = $id;

        if ($this->_id > 0) {

            $sql = " SELECT * FROM usuarios WHERE id_usuario = '" . $this->_id . "' ";

            $query = new Consulta($sql);

            if ($row = $query->VerRegistro()) {
                $this->_nombre = $row['nombre_usuario'];
                $this->_apellidos = $row['apellidos_usuario'];
                $this->_rol = new Rol($row['id_rol']);
                $this->_email = $row['email_usuario'];
                $this->_login = $row['login_usuario'];
                $this->_password = $row['password_usuario'];
                $this->_fecha_ingreso = $row['fecha_ingreso_usuario'];
                $this->_foto = $row['foto_usuario'];

                $sqls = "SELECT * FROM usuarios_secciones WHERE id_usuario = '" . $this->_id . "' ";
                $querys = new Consulta($sqls);
                if ($querys->NumeroRegistros() > 0) {
                    while ($rows = $querys->VerRegistro()) {
                        $this->_secciones[] = array('seccion' => new Seccion($rows['id_seccion']));
                    }
                }
            }
        }
    }

    public function getModulos() {
        $modulos = Array();
        if (is_array($this->_secciones) && count($this->_secciones)) {
            foreach ($this->_secciones as $key => $value) {
                if (is_object($value['seccion'])) {
                    if (!empty($value['seccion'])) {
                        $seccion = $value['seccion'];
                        $modulos[] = $seccion->getModulo();
                    }
                }
            }
        }
        $modulos = array_unique($modulos);
        $modulos = implode(",", $modulos);
        return $modulos;
    }

    public function getSeccionesId() {
        $data;
        $sqls = "SELECT * FROM usuarios_secciones WHERE id_usuario = '" . $this->_id . "' ";
        $querys = new Consulta($sqls);
        if ($querys->NumeroRegistros() > 0) {
            while ($rows = $querys->VerRegistro()) {
                $data[] = $rows['id_seccion'];
            }
        }
        return $data;
    }

    public function getId() {
        return $this->_id;
    }

    public function getLogeado() {
        return $this->_logeado;
    }

    public function setLogeado($valor) {
        $this->_logeado = $valor;
    }

    public function getNombre() {
        return $this->_nombre;
    }

    public function getApellidos() {
        return $this->_apellidos;
    }

    public function getSecciones() {
        return $this->_secciones;
    }

    public function getFoto() {
        return $this->_foto;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getRol() {
        return $this->_rol;
    }

    public function getFechaIngreso() {
        return $this->_fecha_ingreso;
    }

    public function getLogin() {
        return $this->_login;
    }

    public function setLogin($valor) {
        $this->_login = $valor;
    }

    public function __toString() {
        return $this->_nombre . ' ' . $this->_apellidos;
    }

}

?>