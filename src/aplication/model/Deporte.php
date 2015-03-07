<?php

require_once("Ruta.php");
require_once("Evento.php");
require_once("Organizacion.php");
require_once("Proveedor.php");
require_once("Agencia.php");

class Deporte {

    private $_id_deporte,
            $_id_usuario,
            $_nombre_deporte,
            $_descripcion,
            $_icon_map,
            $_imagen_fondo,
            $_modalidades,
            $_rutas,
            $_eventos,
            $_organizaciones,
            $_proveedores,
            $_agencias,
            $_sitios_web,
            $_descripcion_p;

    public function __construct($id = 0) {
        if ($id > 0) {
            $this->_id_deporte = $id;
            $sql = "SELECT * FROM deportes WHERE id_deporte = " . $this->_id_deporte;
            $query = new Consulta($sql);
            $row = $query->VerRegistro();

            $this->_id_usuario = $row['id_usuario'];
            $this->_nombre_deporte = sql_htm($row['nombre_deporte']);
            $this->_descripcion = $row['descripcion_deporte'];
            $this->_descripcion_p = $row['descripcion_deporte_p'];
            $this->_icon_map = $row['image'];
            $this->_imagen_fondo = $row['imagen_fondo'];


            $sql2 = "SELECT * FROM modalidades WHERE id_deporte = " . $this->_id_deporte . " ORDER BY orden_modalidad ASC";
            $query2 = new Consulta($sql2);
            while ($row2 = $query2->VerRegistro()) {
                $this->_modalidades[] = array(
                    'id_modalidad' => $row2['id_modalidad'],
                    'nombre_modalidad' => sql_htm($row2['nombre_modalidad'])
                );
            }
        }
    }

    public function getRutas() {

        if ($this->_id_deporte != 15) { //(SI NO ES)  15 es el ID del deporte "Otros" que esta en la tabla Deportes
            $sql = "SELECT * FROM rutas INNER JOIN lugares USING(id_lugar)
                    WHERE id_deporte = " . $this->_id_deporte . " ORDER BY nombre_lugar ";
            $query2 = new Consulta($sql);
            while ($row2 = $query2->VerRegistro()) {
                $this->_rutas[] = array(
                    'id_lugar' => $row2['id_lugar'],
                    'nombre_lugar' => sql_htm($row2['nombre_lugar']),
                    'ubicacion_lugar' => $row2['ubicacion_lugar'],
                    'descripcion_ruta' => sql_htm($row2['descripcion_ruta']),
                    'lat_lugar' => $row2['lat_lugar'],
                    'lng_lugar' => $row2['lng_lugar']
                );
            }
        } else {
            $sql = "SELECT * FROM rutas_otros 
                    INNER JOIN lugares USING(id_lugar)
                    INNER JOIN modalidades USING(id_modalidad)
                    WHERE id_deporte = " . $this->_id_deporte . " ORDER BY nombre_lugar ";
            $query2 = new Consulta($sql);
            while ($row2 = $query2->VerRegistro()) {
                $this->_rutas[] = array(
                    'id_lugar' => $row2['id_lugar'],
                    'nombre_lugar' => sql_htm($row2['nombre_lugar']),
                    'nombre_modalidad' => sql_htm($row2['nombre_modalidad']),
                    'ubicacion_lugar' => $row2['ubicacion_lugar'],
                    'descripcion_ruta' => sql_htm($row2['descripcion_rutas_otros']),
                    'lat_lugar' => $row2['lat_lugar'],
                    'lng_lugar' => $row2['lng_lugar']
                );
            }
        }
    }

    public function getEventos() {
        $sql = "SELECT * FROM deportes_eventos
                    INNER JOIN eventos USING(id_evento)
                    WHERE id_deporte = " . $this->_id_deporte;
        $query2 = new Consulta($sql);
        while ($row2 = $query2->VerRegistro()) {
            $this->_eventos[] = array(
                'id_evento' => $row2['id_evento'],
                'titulo_evento' => sql_htm($row2['titulo_evento']),
                'fecha_evento' => $row2['fecha_evento'],
                'lugar_evento' => $row2['lugar_evento'],
                'descripcion_evento' => sql_htm($row2['descripcion_evento']),
                'imagen_evento' => $row2['image']
            );
        }
    }

    public function getOrganizaciones() {
        $sql = "SELECT * FROM deportes_organizaciones
                    INNER JOIN organizaciones USING(id_organizacion)
                    WHERE id_deporte = " . $this->_id_deporte;
        $query = new Consulta($sql);
        while ($row2 = $query->VerRegistro()) {
            $this->_organizaciones[] = array(
                'id_organizacion' => $row2['id_organizacion'],
                'nombre_organizacion' => sql_htm($row2['nombre_organizacion']),
                'website_organizacion' => $row2['website_organizacion'],
                'imagen_organizacion' => $row2['image'],
                'descripcion_organizacion' => sql_htm($row2['descripcion_organizacion']),
                'telefono_organizacion' => $row2['telefono_organizacion'],
                'direccion_organizacion' => $row2['direccion_organizacion'],
                'email_organizacion' => $row2['email_organizacion']
            );
        }
    }

    public function getProveedores() {
        $sql = "SELECT * FROM deportes_proveedores
                    INNER JOIN proveedores USING(id_proveedor)
                    WHERE id_deporte = " . $this->_id_deporte;
        $query = new Consulta($sql);
        while ($row2 = $query->VerRegistro()) {
            $this->_proveedores[] = array(
                'id_proveedor' => $row2['id_proveedor'],
                'nombre_proveedor' => sql_htm($row2['nombre_proveedor']),
                'direccion_proveedor' => $row2['direccion_proveedor'],
                'telefono_proveedor' => $row2['telefono_proveedor'],
                'website_proveedor' => $row2['website_proveedor'],
                'descripcion_proveedor' => sql_htm($row2['descripcion_proveedor']),
                'imagen_proveedor' => $row2['image']
            );
        }
    }

    public function getAgencias() {
        $sql = "SELECT * FROM deportes_agencias
                    INNER JOIN agencias USING(id_agencia)
                    WHERE id_deporte = " . $this->_id_deporte;
        $query = new Consulta($sql);
        while ($row2 = $query->VerRegistro()) {
            $this->_agencias[] = array(
                'id_agencia' => $row2['id_agencia'],
                'nombre_agencia' => sql_htm($row2['nombre_agencia']),
                'direccion_agencia' => $row2['direccion_agencia'],
                'telefono_agencia' => $row2['telefono_agencia'],
                'website_agencia' => $row2['website_agencia'],
                'descripcion_agencia' => sql_htm($row2['descripcion_agencia']),
                'imagen_agencia' => $row2['image']
            );
        }
    }

    public function getSitiosWeb() {
        $sql = "SELECT * FROM deportes_sitios_web
                    INNER JOIN sitios_web USING(id_sitio_web)
                    WHERE id_deporte = " . $this->_id_deporte;
        $query = new Consulta($sql);
        while ($row2 = $query->VerRegistro()) {
            $this->_sitios_web[] = array(
                'id_sitio_web' => $row2['id_sitio_web'],
                'titulo_sitio_web' => $row2['titulo_sitio_web'],
                'url_sitio_web' => $row2['url_sitio_web'],
                'descripcion_sitio_web' => $row2['descripcion_sitio_web'],
                'imagen_sitio' => $row2['image']
            );
        }
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

}

?>
