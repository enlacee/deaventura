<?php

//header("Content-Type: text/html;charset=utf-8");
class Deportes {

    private $_msgbox;
    private $_usuario;

    public function __construct(Msgbox $msg = NULL, Usuario $usuario = NULL) {
        $this->_msgbox = $msg;
        $this->_usuario = $usuario;
    }

    public function newDeportes() {
        ?>
        <fieldset id="form">
            <legend>Editar Deporte</legend>
            <form action="" method="post" name="form_deportes" enctype="multipart/form-data"> 
                <div class="button-actions">
                    <input type="reset" class="button" value="CANCELAR" name="cancelar">
                    <input type="button" name="" value="GUARDAR" onclick="return valida_deportes('add','')"  />
                </div>
                <ul>
                    <li><label><strong> Nombre del Deporte: </strong></label><input type="text" class="text ui-widget-content ui-corner-all" size="50" name="nom_deporte" value=""></li>
                    <li><label><strong> Descripcion Principal: </strong></label><textarea rows="8" cols="70" name="descripcion_deporte_p" ></textarea>
                    <li><label><strong> Descripcion del Deporte: </strong></label><textarea rows="8" cols="70" name="descripcion_deporte" ></textarea>
                    <li><label><strong> Icono del Mapa : </strong></label> <input name="image" id="image" type="file" size="50" class="text ui-widget-content ui-corner-all" /></li>
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function addDeportes() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $imagen = "";


            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../aplication/webroot/imgs/catalogo/thumb_');
            $thumbnail->SetTransparencia(true); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(32, 37, $nombre);
        }

        $sqla = "INSERT INTO deportes(id_usuario,nombre_deporte,descripcion_deporte_p,descripcion_deporte,image) 
                VALUES (
                   '" . $this->_usuario->getId() . "',
                   '" . $_POST['nombre_deporte'] . "',
                   '" . addslashes($_POST['descripcion_deporte_p']) . "',
                   '" . addslashes($_POST['descripcion_deporte']) . "',
                   'thumb_" . $nombre . "')";
//echo $sqla;		
        $query = new Consulta($sqla);

        $this->_msgbox->setMsgbox('Deporte grabado correctamente', 2);
        location("deportes.php");
    }

    public function listDeportes() {
        $sql = " SELECT * FROM deportes ";
        $query = new Consulta($sql);
        ?>
        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Deportes</th>
                        <th class='titulo' align="center" width="120">Opciones</th>
                        <th class='titulo' align="center" width="5">Mod</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_deporte'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>folder.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_deporte']) . "</b>" ?></div>

                        <div class="options2">
                            <a title="Modalidades" class="tooltip" href="modalidades.php?id_dep=<?php echo $rowp['id_deporte'] ?>"><img src="<?php echo _admin_ ?>file.png"></a> &nbsp;
                        </div> 
                        <div class="options">
                            <a title="Editar" class="tooltip" href="deportes.php?id=<?php echo $rowp['id_deporte'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('deportes.php','<?php echo $rowp['id_deporte'] ?>','delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;&nbsp;
                            <a title="Tags" class="tooltip" href="tags.php?id_dep=<?php echo $rowp['id_deporte'] ?>"><img src="<?php echo _admin_ ?>tag.png"></a> &nbsp;
                        </div>
                    </li>
                    <?php
                    $y++;
                }
                ?>
            </ul>
            <br class="clear" />
        </div>
        <?php
    }

    public function editDeportes() {
        /* $query = new Consulta("SELECT id_deporte, nombre_deporte, image FROM deportes WHERE id_deporte = '" . $_GET['id'] . "'");
          Form::getForm($query, "edit", "deportes.php", "", "", "img"); */
/*

 */
        $obj = new Deporte($_GET['id']);
        ?>
        <fieldset id="form">
            <legend>Editar Deporte</legend>
            <form action="" method="post" name="form_deportes" enctype="multipart/form-data"> 
                <div class="button-actions">
                    <input type="reset" class="button" value="CANCELAR" name="cancelar">  
                    <input type="button" class="button" onclick="return valida_deportes('update','<?php echo $_GET['id'] ?>')" value="ACTUALIZAR" name="actualizar">	
                </div>
                <ul>
                    <li><label><strong> Nombre del Deporte: </strong></label><input type="text" class="text ui-widget-content ui-corner-all" size="50" name="nom_deporte" value="<?php echo $obj->__get("_nombre_deporte") ?>"></li>
                    <li><label><strong> Descripcion Principal: </strong></label><textarea rows="8" class="textarea tinymce" style="height: 250px" cols="70" name="descripcion_deporte_p" ><?php echo $obj->__get("_descripcion_p") ?></textarea>
                    <li><label><strong> Descripcion del Deporte: </strong></label><textarea rows="8" cols="70" name="descripcion_deporte" ><?php echo $obj->__get("_descripcion") ?></textarea>
                    <li><label><strong> Icono del Mapa : </strong></label> <input name="image" id="image" type="file" size="50" class="text ui-widget-content ui-corner-all" /></li>
                    <li><label></label><img src="<?php echo _catalogo_ . $obj->__get("_icon_map") ?>" /></li>
                </ul>

            </form>
        </fieldset>
        <?php
    }

    public function updateDeportes() {
        $imagen = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $imagen = "";
/*
descripcion_deporte_p

 */
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../aplication/webroot/imgs/catalogo/thumb_');
            $thumbnail->SetTransparencia(true); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(32, 37, $nombre);


            $imagen = ", image = 'thumb_" . $nombre . "'";
            $this->deleteArchivoxDeporte($_GET['id']);
        }

        $query = new Consulta("UPDATE deportes SET  nombre_deporte='" . htm_sql($_POST['nom_deporte']) . "',
                                                    descripcion_deporte_p='" . addslashes($_POST['descripcion_deporte_p']) . "',
                                                    descripcion_deporte='" . addslashes($_POST['descripcion_deporte']) . "'
                                               " . $imagen . " WHERE id_deporte = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente el Deporte.', 2);
        location("deportes.php");

    }

    public function deleteArchivoxDeporte($id) {
        $query = new Consulta("SELECT image FROM deportes WHERE id_deporte = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_files_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function deleteDeportes() {
        $this->deleteArchivoxDeporte($_GET['id']);
        $query = new Consulta("DELETE  FROM deportes WHERE id_deporte = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("deportes.php");
    }

    public function getDeportes($id = "", $filtro = "") {

        $sql = "";
        if ($id != "") {
            $sql = "SELECT id_deporte, nombre_deporte FROM deportes WHERE id_deporte = '" . $id . "'";
        } else if ($filtro != "") {
            $sql = "SELECT id_deporte, nombre_deporte FROM deportes INNER JOIN " . $filtro . " USING(id_deporte) GROUP BY nombre_deporte";
        } else {
            $sql = "SELECT id_deporte, nombre_deporte FROM deportes WHERE estado_deporte = 1 ORDER BY orden_deporte";
        }

        $query = new Consulta($sql);
        $retorno = array();
        while ($row = $query->VerRegistro()) {
            $retorno[] = array(
                'id_deporte' => $row['id_deporte'],
                'nombre_deporte' => sql_htm($row['nombre_deporte'])
            );
        }
        return $retorno;
    }

    public function get_lista_check() {
        $sql2 = " SELECT * FROM deportes ";
        $query2 = new Consulta($sql2);
        while ($rowp = $query2->VerRegistro()) {
            echo '<input name="deporte[]" class="chk_deporte" type="checkbox" value="' . $rowp['id_deporte'] . '" />' . sql_htm($rowp['nombre_deporte']) . '<br/>';
            echo '<label></label>';
        }
    }

    public function get_deportes_vinculados($tabla, $idWhere, $id) {
        $sql2 = " SELECT * FROM deportes ";
        $sql3 = " SELECT id_deporte FROM " . $tabla . " WHERE " . $idWhere . " = '" . $id . "'";
        $query2 = new Consulta($sql2);
        $query3 = new Consulta($sql3);
        $array;
        while ($rowd = $query3->VerRegistro()) {
            $array[] = $rowd["id_deporte"];
        }
        while ($rowp = $query2->VerRegistro()) {
            ?>
            <input name="deporte[]" class="chk_deporte" type="checkbox" value="<?php echo $rowp['id_deporte'] ?>" <?php if (in_array($rowp['id_deporte'], $array)) echo 'checked="checked"'; ?> /><?php echo sql_htm($rowp['nombre_deporte']) ?><br/>
            <label></label>
            <?php
        }
    }

    public function consultaUrl($sql, $arr) {
        $valores[$arr[0]] = 0;
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $row = $query->VerRegistro();
            foreach ($arr as $value) {
                $valores[$value] = $row[$value];
            }
        }
        return $valores;
    }

}
?>
