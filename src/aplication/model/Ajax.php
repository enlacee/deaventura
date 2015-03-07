<?php

class Ajax {

    private $_idioma;
    private $_cuenta;

    public function __construct(Idioma $idioma = NULL, Cuenta $cuenta = NULL) {
        $this->_idioma = $idioma;
        $this->_cuenta = $cuenta;
    }

    function ordenarAjax() {
        foreach ($_GET['list_item'] as $position => $item) {
            $type_val = explode("|", $item);
            if ($type_val[1] == 'mod') {
                $sql = "UPDATE modalidades SET orden_modalidad = $position WHERE id_modalidad	 = $type_val[0] ";
                $query = new Consulta($sql);
            }
        }
    }

    function autocompleteCategoriasAjax() {
        $obj_cat = new Categorias();
        $data = $obj_cat->getCategoriaXCriterio($_GET['term']);
        if (count($data) != 0) {
            echo encode_json($data);
        } else {
            echo "[ ]";
        }
    }

    function viewUserAjax() {
        if ($_GET['id']) {
            $obj = new Usuario($_GET['id']);
            ?>

            <ul id="datos_usuario">
                <li><label>Nombre:</label> <div class="value_field"><?php echo $obj->getNombre(); ?></div></li>
                <li><label>Apellidos:</label> <div class="value_field"><?php echo $obj->getApellidos(); ?></div></li>
                <li><label>Cargo:</label> <div class="value_field"><?php echo $obj->getRol()->getNombre(); ?></div></li>
                <li><label>Email:</label> <div class="value_field"><?php echo $obj->getEmail(); ?></div></li>
                <li><label>Login:</label> <div class="value_field"><?php echo $obj->getLogin(); ?></div></li>
            </ul>
            <?php
        }
    }

    function ordenarArchivos($order) {
        foreach ($order as $position => $item) {
            $arr = explode("arch", $item);

            if ($arr[0] == 'arch') {
                $query = new Consulta("UPDATE aventuras_archivos SET orden_aventuras_archivos = $position WHERE id_aventuras_archivo = $arr[1] ");
            }
        }
    }

    function llenarCboDeportes() {
        $sql = " SELECT id_deporte, nombre_deporte FROM deportes ";
        $query = new Consulta($sql);

        if ($query > 0) {
            while ($row = $query->VerRegistro()) {
                echo '<option value="' . $row["id_deporte"] . '">' . sql_htm($row["nombre_deporte"]) . '</option>';
            }
        }
    }

    function llenarCboModalidades($id) {
        $sql = "SELECT * FROM modalidades WHERE id_deporte=$id";
        $query = new Consulta($sql);
        if ($query > 0) {
            while ($row = $query->VerRegistro()) {
                echo '<option value="' . $row["id_modalidad"] . '">' . sql_htm($row["nombre_modalidad"]) . '</option>';
            }
        }
    }

    function llenarCboAgencias() {
        $sql = "SELECT id_agencia, nombre_agencia FROM agencias";
        $query = new Consulta($sql);
        if ($query > 0) {
            while ($row = $query->VerRegistro()) {
                echo '<option value="' . $row["id_agencia"] . '">' . sql_htm($row["nombre_agencia"]) . '</option>';
            }
        }
    }

    function comparteAventura_step2($deporte, $modalidad, $agencia, $titulo, $lugar, $desc, $images, $videos) {
        $_SESSION["miaventura"]['idDeporte'] = $deporte;
        $_SESSION["miaventura"]['idModalidad'] = $modalidad;
        $_SESSION["miaventura"]['idAgencia'] = $agencia;
        $_SESSION["miaventura"]['titulo'] = addslashes($titulo);
        $_SESSION["miaventura"]['lugar'] = addslashes($lugar);
        $_SESSION["miaventura"]['descripcion'] = addslashes($desc);

        if ($images != "") {
            $array = explode(",", $images);
            $_SESSION["miaventura"]['name_images'] = $array;
            $_SESSION['files_act'] = $array;
        }
        if ($videos != "") {
            $_SESSION["miaventura"]['name_videos'] = explode(",", $videos);
        }
        //echo $videos;
    }

    function guardarTemporal_img($img) {
        $temp[] = $img;
        if (in_array($img, $temp)) {
            $_SESSION['files_act'][] = $img;
        };
        //print_r($_SESSION['files_act']);
    }

    function eliminar_archivo_aventura($id) {
        $query = new Consulta("SELECT nombre_aventuras_archivos FROM aventuras_archivos WHERE id_aventuras_archivo = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['nombre_aventuras_archivos'] != '') {
            $nombre = _host_avfiles_users_ . $row['nombre_aventuras_archivos'];
            if (file_exists($nombre))
                unlink($nombre);

            $thumb = _host_avfiles_users_ . 'thumbnail/' . $row['nombre_aventuras_archivos'];
            if (file_exists($thumb))
                unlink($thumb);
        }

        $query = new Consulta("DELETE FROM aventuras_archivos WHERE id_aventuras_archivo = '" . $id . "'");
    }

    function eliminar_archivo_xnombre($nombreArch) {
        if ($nombreArch != '') {
            $nombre = _host_avfiles_users_ . $nombreArch;
            if (file_exists($nombre))
                unlink($nombre);

            $thumb = _host_avfiles_users_ . 'thumbnail/' . $nombreArch;
            if (file_exists($thumb))
                unlink($thumb);
        }
    }

    /* FAVORITOS */

    function agregar_favoritos($id) {
        $favorito = new Favoritos(NULL, $this->_cuenta);
        $favorito->addFavoritos($id);
    }

    /* EVENTOS */

    function asistencia_evento($caso, $id) {

        if ($caso == 0) {
            $id_cliente = $this->_cuenta->__get("_cliente")->__get("_id");
            if ($id_cliente > 0) {
                //Comprobar si ya esta agregado
                if ($this->verificar_asistencia_evento($id, $id_cliente) > 0) {
                    echo 1;
                } else {
                    $query = new Consulta("INSERT INTO clientes_eventos(id_cliente,id_evento) VALUES ('" . $id_cliente . "','" . $id . "')");
                    echo 3;
                }
            } else {
                echo 0;
            }
        } else {
            $query = new Consulta("DELETE FROM clientes_eventos WHERE id_evento = '" . $id . "'");
        }
    }

    function verificar_asistencia_evento($id, $id_cliente) {
        $sql = "SELECT * FROM clientes_eventos WHERE id_evento = '" . $id . "' AND id_cliente = '" . $id_cliente . "'";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            return 1;
        } else {
            return 0;
        }
    }


}
?>